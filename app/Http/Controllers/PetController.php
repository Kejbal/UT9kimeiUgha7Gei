<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PetController extends Controller
{
    protected $apiBase = 'https://petstore.swagger.io/v2';

    public function index(Request $request, string $status = 'available')
    {
        $validatedStatus = in_array($status, ['available', 'pending', 'sold']) ? $status : 'available';

        $response = Http::get("{$this->apiBase}/pet/findByStatus", [
            'status' => $validatedStatus,
        ]);

        if ($response->failed()) {
            return back()->withErrors('Failed to fetch pets from API.');
        }

        $pets = $response->json();
        return view('pets.index', compact('pets', 'validatedStatus'));
    }

    public function create()
    {
        return view('pets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id'            => 'required|numeric',
            'name'          => 'required|string',
            'photoUrls'     => 'nullable|array',
            'photoUrls.*'   => 'nullable|url',
            'category.id'   => 'nullable|numeric',
            'category.name' => 'nullable|string',
            'tags'          => 'nullable|array',
            'tags.*.id'     => 'nullable|numeric',
            'tags.*.name'   => 'nullable|string',
            'status'        => 'required|string',
        ]);

        $photoUrls = $request->photoUrls ?? [];
        $photoUrls = array_filter($request->photoUrls ?? [], fn($url) => ! empty($url));

        $tags = $request->tags ?? [];
        $tags = array_filter($request->tags ?? [], fn($tag) => ! empty($tag['name']) && ! empty($tag['id']));

        $response = Http::post("{$this->apiBase}/pet", [
            'id'        => (int) $request->id,
            'name'      => $request->name,
            'photoUrls' => $photoUrls,
            'category'  => [
                'id'   => (int) $request->category_id,
                'name' => $request->category_name,
            ],
            'tags'      => $tags,
            'status'    => $request->status,
        ]);

        if ($response->failed()) {
            return back()->withErrors('Failed to add pet.');
        }

        return redirect()->route('pets.index')->with('success', 'Pet added successfully!');
    }

    public function edit(int $id)
    {
        $response = Http::get("{$this->apiBase}/pet/{$id}");

        if ($response->failed()) {
            return redirect()->route('pets.index')->withErrors('Failed to fetch pet data.');
        }

        $pet = $response->json();

        return view('pets.edit', compact('pet'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name'          => 'required|string',
            'photoUrls'     => 'nullable|array',
            'photoUrls.*'   => 'nullable|url',
            'category.id'   => 'nullable|numeric',
            'category.name' => 'nullable|string',
            'tags'          => 'nullable|array',
            'tags.*.id'     => 'nullable|numeric',
            'tags.*.name'   => 'nullable|string',
            'status'        => 'required|string',
        ]);

        $photoUrls = $request->photoUrls ?? [];
        $photoUrls = array_filter($request->photoUrls ?? [], fn($url) => ! empty($url));

        $tags = $request->tags ?? [];
        $tags = array_filter($request->tags ?? [], fn($tag) => ! empty($tag['name']) && ! empty($tag['id']));

        $response = Http::post("{$this->apiBase}/pet", [
            'id'        => (int) $id,
            'name'      => $request->name,
            'photoUrls' => $photoUrls,
            'category'  => [
                'id'   => (int) $request->category_id,
                'name' => $request->category_name,
            ],
            'tags'      => $tags,
            'status'    => $request->status,
        ]);

        if ($response->failed()) {
            return back()->withErrors('Failed to update pet.');
        }

        return redirect()->route('pets.index')->with('success', 'Pet updated successfully!');
    }

    public function destroy($id)
    {
        $response = Http::delete("{$this->apiBase}/pet/{$id}");

        if ($response->failed()) {
            return back()->withErrors('Failed to delete pet.');
        }

        return redirect()->route('pets.index')->with('success', 'Pet deleted successfully!');
    }

    public function uploadImage(Request $request, int $id)
    {
        $request->validate([
            'photo' => 'required|file|image|max:2048',
        ]);

        $response = Http::attach(
            'file', file_get_contents($request->file('photo')->getRealPath()), $request->file('photo')->getClientOriginalName()
        )->post("{$this->apiBase}/pet/{$id}/uploadImage", [
            'additionalMetadata' => '',
        ]);

        if ($response->failed()) {
            return back()->withErrors('Failed to upload image.');
        }

        return redirect()->route('pets.edit', ['id' => $id])->with('success', 'Image uploaded successfully!');
    }
}
