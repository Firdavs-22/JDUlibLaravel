<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Request;
use App\Traits\HttpResponse;
use App\Enum\StatusEnum;
use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use App\Models\BookSeries;

class BookController extends Controller
{
    use HttpResponse;

    public function store(StoreBookRequest $request)
    {
        $request->validated($request->all());

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'category_id' => $request->category_id,
        ]);

        if (isset($request->book_series) && is_array($request->book_series)) {
            foreach ($request->book_series as $serial_id) {
                BookSeries::create([
                    'serial_id' => $serial_id,
                    'book_id' => $book->id,
                ]);
            }
        }

        $book->load('bookSeries');

        return $this->success([
            'book' => $book
        ]);
    }

    public function index($page)
    {
        $perPage = 50;
        $offset = ($page - 1) * $perPage;

        $books = Book::query()->where(['status' => StatusEnum::ON])->with('bookSeries')->offset($offset)->limit($perPage)->get();
        $hasNext = Book::query()->where(['status' => StatusEnum::ON])->offset($offset + $perPage)->limit($perPage)->exists();
        $total = Book::query()->where(['status' => StatusEnum::ON])->count();

        return $this->success([
            'books' => $books,
            'pagination' => [
                'hasNext' => $hasNext,
                'total' => $total,
                'currentPage' => $page
            ]
        ]);
    }

    public function show($id)
    {
        $book = Book::query()
            ->where(['id' => $id])
            ->where(['status' => StatusEnum::ON])
            ->first();

        if (!$book) {
            return $this->error('', 'The requested book was not found', 404);
        }

        $book->load('bookSeries');

        return $this->success([
            'book' => $book
        ]);
    }

    public function update(UpdateBookRequest $request, $id)
    {
        $request->validated($request->all());

        $book = Book::query()
            ->where(['id' => $id])
            ->where(['status' => StatusEnum::ON])->first();

        if (!$book) {
            return $this->error('', 'The requested book was not found', 404);
        }

        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'category_id' => $request->category_id,
        ]);

        BookSeries::query()->where([
            'book_id' => $book->id
        ])->update(['status' => StatusEnum::OFF]);

        if (isset($request->book_series) && is_array($request->book_series)) {
            foreach ($request->book_series as $serial_id) {
                BookSeries::query()
                    ->where(['book_id' => $book->id])
                    ->where(['serial_id' => $serial_id])
                    ->updateOrInsert([
                        'serial_id' => $serial_id,
                        'book_id' => $book->id,
                    ], ['status' => StatusEnum::ON]);
            }
        }

        $book->load('bookSeries');

        return $this->success([
            'book' => $book,
        ]);
    }

    public function destroy($id)
    {
        $book = Book::query()
            ->where(['id' => $id])
            ->where(['status' => StatusEnum::ON])->first();

        if (!$book) {
            return $this->error('', 'The requested book was not found', 404);
        }

        $book->update(['status' => StatusEnum::OFF]);

        return $this->success(null, 'You have successfully deleted book');
    }
}
