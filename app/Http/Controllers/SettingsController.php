<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
    public function index()
    {
        $setting = DB::table('settings')
            ->where('id', '=', 1)
            ->get();

        return view('settings.index', compact(['setting']));
    }

    public function update(Request $request, Settings $settings)
    {
        $request->validate([
            'dark_mode' => 'required|boolean',
            'show_versions_footer' => 'required|boolean',
            'show_server_value_ip' => 'required|boolean',
            'show_server_value_hostname' => 'required|boolean',
            'show_server_value_provider' => 'required|boolean',
            'show_server_value_location' => 'required|boolean',
            'show_server_value_price' => 'required|boolean',
            'show_server_value_yabs' => 'required|boolean',
            'default_currency' => 'required',
            'default_server_os' => 'required',
            'due_soon_amount' => 'required|integer|between:0,12',
            'recently_added_amount' => 'required|integer|between:0,12',
            'currency' => 'required|string|size:3'
        ]);

        DB::table('settings')
            ->where('id', 1)
            ->update([
                'dark_mode' => $request->dark_mode,
                'show_versions_footer' => $request->show_versions_footer,
                'show_servers_public' => $request->show_servers_public,
                'show_server_value_ip' => $request->show_server_value_ip,
                'show_server_value_hostname' => $request->show_server_value_hostname,
                'show_server_value_provider' => $request->show_server_value_provider,
                'show_server_value_location' => $request->show_server_value_location,
                'show_server_value_price' => $request->show_server_value_price,
                'show_server_value_yabs' => $request->show_server_value_yabs,
                'default_currency' => $request->default_currency,
                'default_server_os' => $request->default_server_os,
                'due_soon_amount' => $request->due_soon_amount,
                'recently_added_amount' => $request->recently_added_amount,
                'dashboard_currency' => $request->currency,
            ]);

        Settings::setSettingsToSession($settings);

        Cache::forget('due_soon');//Main page due_soon cache
        Cache::forget('recently_added');//Main page recently_added cache
        Cache::forget('pricing_breakdown');//Main page pricing breakdown

        Cache::forget('settings');//Main page settings cache

        return redirect()->route('settings.index')
            ->with('success', 'Settings Updated Successfully.');
    }

}
