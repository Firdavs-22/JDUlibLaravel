<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateBookRequest;
use App\Models\OccupiedBook;
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

    public function index()
    {


        $books = Book::query()->where(['status' => StatusEnum::ON])->get();
        $bookIdList = $books->modelKeys();
        $bookSeries = BookSeries::query()
            ->whereIn('book_id', $bookIdList)
            ->where(['status' => StatusEnum::ON])
            ->get();
        $bookSeriesIdList = $bookSeries->modelKeys();
        $bookSeries = $bookSeries->groupBy('book_id');

        $takenBooks = OccupiedBook::query()->whereIn('book_series_id', $bookSeriesIdList)
            ->where(['status' => StatusEnum::ON])
            ->whereNull('returned_date')
            ->get()->groupBy('book_series_id');
        $takenBooks->makeHidden(['book_series_id', 'returned_date']);
        foreach ($books as $book) {
            if ($bookSeries->has($book->id)) {
                $book->book_series = $bookSeries[$book->id];
                foreach ($book->book_series as $book_serie) {
                    if ($takenBooks->has($book_serie->id)) {
                        $book_serie->taken = $takenBooks[$book_serie->id];
                    }
                }
            }
        }


        return $this->success([
            'books' => $books,

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

        $bookSeries = BookSeries::query()
            ->where('book_id', $book->id)
            ->where(['status' => StatusEnum::ON])
            ->get();
        $bookSeriesIdList = $bookSeries->modelKeys();

        $takenBooks = OccupiedBook::query()->whereIn('book_series_id', $bookSeriesIdList)
            ->where(['status' => StatusEnum::ON])
            ->whereNull('returned_date')
            ->get()->groupBy('book_series_id');
        $takenBooks->makeHidden(['book_series_id', 'returned_date']);

        if ($bookSeries) {
            $book->book_series = $bookSeries;
            foreach ($book->book_series as $book_serie)
                if ($takenBooks->has($book_serie->id)) {
                    $book_serie->taken = $takenBooks[$book_serie->id];
                }
        }

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
