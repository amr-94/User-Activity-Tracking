<?php

namespace App\Repositories;

use App\Models\Setting;
use App\Interfaces\BaseRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingRepository implements BaseRepositoryInterface
{
    public function index($request)
    {
        try {
            $settings = Setting::all()->pluck('value', 'key')->toArray();
            return $settings;
        } catch (Exception $e) {
            Log::error('Error in SettingRepository@index: ' . $e->getMessage());
            throw $e;
        }
    }

    public function find($key)
    {
        try {
            return Setting::where('key', $key)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            Log::error('Setting not found with key: ' . $key);
            throw $e;
        } catch (Exception $e) {
            Log::error('Error in SettingRepository@find: ' . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data)
    {
        try {
            return Setting::create([
                'key' => $data['key'],
                'value' => $data['value']
            ]);
        } catch (Exception $e) {
            Log::error('Error in SettingRepository@create: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($key, array $data)
    {
        try {
            $setting = $this->find($key);
            if (!$setting) {
                return $this->create([
                    'key' => $key,
                    'value' => $data['value']
                ]);
            }

            $setting->update(['value' => $data['value']]);
            return $setting;
        } catch (ModelNotFoundException $e) {
            Log::error('Setting not found for update with key: ' . $key);
            throw $e;
        } catch (Exception $e) {
            Log::error('Error in SettingRepository@update: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete($key)
    {
        try {
            return Setting::where('key', $key)->delete();
        } catch (Exception $e) {
            Log::error('Error in SettingRepository@delete: ' . $e->getMessage());
            throw $e;
        }
    }

    // Additional method specific to settings
    public function get($key, $default = null)
    {
        try {
            $setting = $this->find($key);
            return $setting ? $setting->value : $default;
        } catch (ModelNotFoundException $e) {
            return $default;
        } catch (Exception $e) {
            Log::error('Error in SettingRepository@get: ' . $e->getMessage());
            throw $e;
        }
    }

    public function set($key, $value)
    {
        try {
            return $this->update($key, ['value' => $value]);
        } catch (Exception $e) {
            Log::error('Error in SettingRepository@set: ' . $e->getMessage());
            throw $e;
        }
    }

    public function all()
    {
        try {
            return Setting::all()->pluck('value', 'key')->toArray();
        } catch (Exception $e) {
            Log::error('Error in SettingRepository@all: ' . $e->getMessage());
            throw $e;
        }
    }
}
