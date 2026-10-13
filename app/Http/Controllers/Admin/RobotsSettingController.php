<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RobotsSettingRequest;
use App\Models\RobotsSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RobotsSettingController extends Controller
{
    /**
     * Show the robots.txt settings form.
     */
    public function edit()
    {
        $settings = RobotsSetting::firstOrCreate(
            ['id' => 1],
            RobotsSetting::defaults()
        );

        return view('admin.robots.settings', compact('settings'));
    }

    /**
     * Update the robots.txt settings.
     */
    public function update(RobotsSettingRequest $request)
    {
        DB::transaction(function () use ($request) {
            $settings = RobotsSetting::firstOrCreate(['id' => 1], RobotsSetting::defaults());

            $mode = $request->input('crawling_mode');

            if ($mode === 'production') {
                $data = [
                    'crawling_mode' => 'production',
                    'allow_frontend' => true,
                    'block_backend' => true,
                    'block_api' => true,
                    'block_login' => true,
                    'include_sitemap' => true,
                    'block_images' => false,
                    'block_css' => false,
                    'block_js' => false,
                    'custom_rules' => $request->input('custom_rules'),
                ];
            } elseif ($mode === 'maintenance' || $mode === 'development') {
                $data = [
                    'crawling_mode' => $mode,
                    'allow_frontend' => false,
                    'block_backend' => false,
                    'block_api' => false,
                    'block_login' => false,
                    'include_sitemap' => false,
                    'block_images' => false,
                    'block_css' => false,
                    'block_js' => false,
                    'custom_rules' => $request->input('custom_rules'),
                ];
            } else { // custom mode
                $data = [
                    'crawling_mode' => 'custom',
                    'allow_frontend' => $request->boolean('allow_frontend'),
                    'block_backend' => $request->boolean('block_backend'),
                    'block_api' => $request->boolean('block_api'),
                    'block_login' => $request->boolean('block_login'),
                    'include_sitemap' => $request->boolean('include_sitemap'),
                    'block_images' => $request->boolean('block_images'),
                    'block_css' => $request->boolean('block_css'),
                    'block_js' => $request->boolean('block_js'),
                    'custom_rules' => $request->input('custom_rules'),
                ];
            }

            $settings->update($data);
        });

        Cache::forget('robots_txt_settings');

        return redirect()->route('admin.robots-settings.edit')
            ->with('status', 'Robots.txt settings updated successfully!');
    }

    /**
     * Reset the robots.txt settings to recommended defaults.
     */
    public function reset()
    {
        DB::transaction(function () {
            $settings = RobotsSetting::firstOrCreate(['id' => 1], RobotsSetting::defaults());
            $settings->update(RobotsSetting::defaults());
        });

        Cache::forget('robots_txt_settings');

        return redirect()->route('admin.robots-settings.edit')
            ->with('status', 'Robots.txt settings reset to recommended defaults.');
    }
}
