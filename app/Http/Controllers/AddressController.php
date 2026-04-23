<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Address;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;

class AddressController extends Controller
{
    /**
     * Get all provinces
     */
    public function getProvinces() 
    {
        try {
            $provinces = Province::all();
            return response()->json($provinces);
        } catch (\Exception $e) {
            Log::error('Error getProvinces: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Get cities by province ID
     */
    public function getCities($provinceId) 
    {
        try {
            $province = Province::find($provinceId);
            
            if (!$province) {
                return response()->json([]);
            }
            
            $cities = City::where('province_code', $province->code)->get();
            
            return response()->json($cities);
        } catch (\Exception $e) {
            Log::error('Error getCities: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Get districts by city ID
     */
    public function getDistricts($cityId) 
    {
        try {
            $city = City::find($cityId);
            
            if (!$city) {
                return response()->json([]);
            }
            
            $districts = District::where('city_code', $city->code)->get();
            
            return response()->json($districts);
        } catch (\Exception $e) {
            Log::error('Error getDistricts: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Get postal codes by district name
     */
    public function getPostalCodes(Request $request) 
    {
        try {
            $districtName = $request->query('district_name');
            
            if (!$districtName) {
                return response()->json([]);
            }
            
            $postalCodes = DB::table('kodepos') 
                ->where('kecamatan', 'LIKE', "%{$districtName}%")
                ->get();
            
            return response()->json($postalCodes);
        } catch (\Exception $e) {
            Log::error('Error getPostalCodes: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Store a new address
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'recipient_name' => 'required|string|max:255',
                'phone_number'   => 'required|string|max:20',
                'province'       => 'nullable|string',
                'city'           => 'nullable|string',
                'district'       => 'nullable|string',
                'postal_code'    => 'nullable|string',
                'full_address'   => 'required|string',
                'is_primary'     => 'sometimes|boolean',
            ]);

            $userId = Auth::id();
            $isFirstAddress = Address::where('user_id', $userId)->count() === 0;

            // Jika ini alamat pertama atau user memilih sebagai primary
            if ($request->has('is_primary') || $isFirstAddress) {
                Address::where('user_id', $userId)->update(['is_primary' => 0]);
                $isPrimary = 1;
            } else {
                $isPrimary = 0;
            }

            $address = Address::create([
                'user_id'        => $userId,
                'recipient_name' => $request->recipient_name,
                'phone_number'   => $request->phone_number,
                'full_address'   => $request->full_address,
                'province'       => $request->province,
                'city'           => $request->city,
                'district'       => $request->district,
                'postal_code'    => $request->postal_code,
                'is_primary'     => $isPrimary,
            ]);

            if ($address) {
                return redirect()->back()->with('success', 'Alamat berhasil ditambahkan!');
            } else {
                return redirect()->back()->with('error', 'Gagal menambahkan alamat!');
            }
            
        } catch (\Exception $e) {
            Log::error('Error store address: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get address data for editing
     */
    public function edit($id)
    {
        try {
            $address = Address::where('user_id', Auth::id())->findOrFail($id);
            return response()->json($address);
        } catch (\Exception $e) {
            Log::error('Error edit address: ' . $e->getMessage());
            return response()->json(['error' => 'Address not found'], 404);
        }
    }

    /**
     * Update an address
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'recipient_name' => 'required|string|max:255',
                'phone_number'   => 'required|string|max:20',
                'province'       => 'nullable|string',
                'city'           => 'nullable|string',
                'district'       => 'nullable|string',
                'postal_code'    => 'nullable|string',
                'full_address'   => 'required|string',
                'is_primary'     => 'sometimes|boolean',
            ]);

            $address = Address::where('user_id', Auth::id())->findOrFail($id);
            
            // Jika dijadikan primary, update alamat lain
            if ($request->has('is_primary') && $request->is_primary == 1) {
                Address::where('user_id', Auth::id())
                    ->where('id', '!=', $id)
                    ->update(['is_primary' => 0]);
                $isPrimary = 1;
            } else {
                $isPrimary = $request->has('is_primary') ? 1 : 0;
            }
            
            $address->update([
                'recipient_name' => $request->recipient_name,
                'phone_number'   => $request->phone_number,
                'full_address'   => $request->full_address,
                'province'       => $request->province,
                'city'           => $request->city,
                'district'       => $request->district,
                'postal_code'    => $request->postal_code,
                'is_primary'     => $isPrimary,
            ]);

            return redirect()->back()->with('success', 'Alamat berhasil diupdate!');
            
        } catch (\Exception $e) {
            Log::error('Error update address: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengupdate alamat: ' . $e->getMessage());
        }
    }

    /**
     * Delete an address
     */
    public function destroy($id)
    {
        try {
            $address = Address::where('user_id', Auth::id())->findOrFail($id);
            
            // Cek apakah alamat yang dihapus adalah alamat utama
            $wasPrimary = $address->is_primary;
            
            $address->delete();
            
            // Jika yang dihapus adalah alamat utama, set alamat lain menjadi utama
            if ($wasPrimary) {
                $newPrimary = Address::where('user_id', Auth::id())->first();
                if ($newPrimary) {
                    $newPrimary->update(['is_primary' => 1]);
                }
            }
            
            return redirect()->back()->with('success', 'Alamat berhasil dihapus!');
            
        } catch (\Exception $e) {
            Log::error('Error delete address: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus alamat!');
        }
    }
}