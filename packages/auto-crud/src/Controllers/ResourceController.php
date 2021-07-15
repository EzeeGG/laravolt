<?php

declare(strict_types=1);

namespace Laravolt\AutoCrud\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;

class ResourceController extends Controller
{
    use AuthorizesRequests;

    public function index(string $resource)
    {
        $config = $this->validateResource($resource);

        return view('laravolt::auto-crud.index', compact('config'));
    }

    public function create(string $resource)
    {
        $config = $this->validateResource($resource);
        $fields = collect($config['schema'])
            ->filter(
                function ($item) {
                    return ($item['visibility']['create'] ?? true);
                }
            );

        return view('laravolt::auto-crud.create', compact('config', 'fields'));
    }

    public function store(string $resource)
    {
        $config = $this->validateResource($resource);
        $data = $this->validateData($config['schema']);
        app($config['model'])->create($data);

        return redirect()
            ->route('auto-crud::resource.index', $resource)
            ->withSuccess(sprintf('%s saved', $config['label']));
    }

    public function show(string $resource, $id)
    {
        $config = $this->validateResource($resource);
        $model = app($config['model'])->findOrFail($id);

        return view('laravolt::auto-crud.show', compact('config', 'model'));
    }

    public function edit(string $resource, $id)
    {
        $config = $this->validateResource($resource);
        $model = app($config['model'])->findOrFail($id);
        $fields = collect($config['schema'])
            ->filter(
                function ($item) {
                    return ($item['visibility']['edit'] ?? true);
                }
            );

        return view('laravolt::auto-crud.edit', compact('config', 'model', 'fields'));
    }

    public function update(string $resource, $id)
    {
        $config = $this->validateResource($resource);
        $data = $this->validateData($config['schema']);
        $model = app($config['model'])->findOrFail($id);

        $model->update($data);

        return redirect()
            ->back()
            ->withSuccess(sprintf('%s updated', $config['label']));
    }

    public function destroy(string $resource, $id)
    {
        $config = $this->validateResource($resource);
        $model = app($config['model'])->findOrFail($id);

        $model->delete();

        return redirect()
            ->back()
            ->withSuccess(sprintf('%s deleted', $config['label']));
    }

    protected function validateResource(string $resource)
    {
        $key = "laravolt.auto-crud.resources.$resource";

        if (! config()->has($key)) {
            abort(404);
        }

        // Module level authorization
        $this->authorize(config('laravolt.auto-crud.permission'));

        // Collection level authorization
        $this->authorize(config("laravolt.auto-crud.resources.$resource.permission"));

        return config()->get($key) + ['key' => $resource];
    }

    protected function validateData($schema)
    {
        $data = [];
        foreach ($schema as $field) {
            $key = $field['name'];
            if ($field['type'] === 'uploader') {
                $data[$key] = Arr::first(request()->media($key)->toArray());
            } else {
                $data[$key] = request()->input($key);
            }
        }

        return $data;
    }
}
