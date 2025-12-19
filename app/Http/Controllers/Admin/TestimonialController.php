<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Models\Testimonial;
use Inertia\Inertia;

class TestimonialController extends Controller
{
    public function index()
    {
        $items = Testimonial::latest()->paginate(20)->withQueryString();
        return Inertia::render('admin/TestimonialsIndex', ['items' => $items]);
    }

    public function create()
    {
        return Inertia::render('admin/testimonials/Form', ['item' => new Testimonial()]);
    }

    public function store(TestimonialRequest $request)
    {
        Testimonial::create($request->validated());
        return redirect()->route('admin.testimonials.index')->with('success', 'Témoignage créé');
    }

    public function edit(Testimonial $testimonial)
    {
        return Inertia::render('admin/testimonials/Form', ['item' => $testimonial]);
    }

    public function update(TestimonialRequest $request, Testimonial $testimonial)
    {
        $testimonial->update($request->validated());
        return redirect()->route('admin.testimonials.index')->with('success', 'Témoignage mis à jour');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Témoignage supprimé');
    }
}

