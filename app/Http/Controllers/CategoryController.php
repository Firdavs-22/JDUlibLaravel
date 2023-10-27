<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Enum\StatusEnum;
use App\Traits\HttpResponse;

class CategoryController extends Controller
{
    use HttpResponse;

    protected function makeHiddenCategoriesId($categories)
    {
        $categories->each(function ($category) {
            $this->makeHiddenCategoryId($category);
        });
    }

    protected function makeHiddenCategoryId($category)
    {
        $category->books->makeHidden('category_id');
    }

    public function store(StoreCategoryRequest $request)
    {
        $request->validated($request->all());

        $category = Category::create([
            'name' => $request->name,
        ]);

        return $this->success([
            'category' => $category
        ]);
    }

    public function index()
    {
        $categories = Category::query()->where(['status' => StatusEnum::ON])->get();

        return $this->success([
            'categories' => $categories,
        ]);
    }

    public function show($id)
    {
        $category = Category::query()
            ->where(['id' => $id])
            ->where(['status' => StatusEnum::ON])
            ->with('books')
            ->first();

        if (!$category) {
            return $this->error('', 'The requested category was not found', 404);
        }

        $this->makeHiddenCategoryId($category);

        return $this->success([
            'category' => $category
        ]);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $request->validated($request->all());

        $category = Category::query()
            ->where(['id' => $id])
            ->where(['status' => StatusEnum::ON])->first();

        if (!$category) {
            return $this->error('', 'The requested category was not found', 404);
        }

        $category->update($request->all());

        return $this->success([
            'category' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = Category::query()
            ->where(['id' => $id])
            ->where(['status' => StatusEnum::ON])->first();

        if (!$category) {
            return $this->error('', 'The requested category was not found', 404);
        }

        $category->update(['status' => StatusEnum::OFF]);

        return $this->success(null, 'You have successfully deleted category');
    }
}
