<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::with(['car.brand', 'cars.brand'])->latest()->paginate(20);
        $cars = Car::where('is_active', true)->with('brand')->get();

        return view('crm.offers.index', compact('offers', 'cars'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'car_ids' => 'required|array',
            'car_ids.*' => 'exists:cars,id',
            'title' => 'required|array',
            'title.ar' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
            'description' => 'nullable|array',
            'description.ar' => 'nullable|string',
            'description.en' => 'nullable|string',
            'discount_percent' => 'nullable|integer|min:1|max:100|prohibited_with:discount_value',
            'discount_value' => 'nullable|integer|min:0|prohibited_with:discount_percent',
            'special_installment' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('offers', 'public');
        }

        if (!empty($data['discount_percent'])) {
            $data['discount_value'] = null;
        } elseif (!empty($data['discount_value'])) {
            $data['discount_percent'] = null;
        }

        $offer = Offer::create($data);
        $offer->cars()->sync($request->car_ids);

        return back()->with('success', 'تمت إضافة العرض');
    }

    public function update(Request $request, Offer $offer)
    {
        $data = $request->validate([
            'car_ids' => 'required|array',
            'car_ids.*' => 'exists:cars,id',
            'title' => 'required|array',
            'title.ar' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
            'description' => 'nullable|array',
            'description.ar' => 'nullable|string',
            'description.en' => 'nullable|string',
            'discount_percent' => 'nullable|integer|min:1|max:100|prohibited_with:discount_value',
            'discount_value' => 'nullable|integer|min:0|prohibited_with:discount_percent',
            'special_installment' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        if (!empty($data['discount_percent'])) {
            $data['discount_value'] = null;
        } elseif (!empty($data['discount_value'])) {
            $data['discount_percent'] = null;
        }

        if ($request->hasFile('image')) {
            if ($offer->image && \Storage::disk('public')->exists($offer->image)) {
                \Storage::disk('public')->delete($offer->image);
            }
            $data['image'] = $request->file('image')->store('offers', 'public');
        }

        $offer->update($data);
        $offer->cars()->sync($request->car_ids);

        return back()->with('success', 'تم تحديث العرض');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();

        return back()->with('success', 'تم حذف العرض');
    }
}
