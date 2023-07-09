<?php

namespace Astrotomic\Tmdb\Requests\Search;

use Astrotomic\Tmdb\Facades\Tmdb;
use Astrotomic\Tmdb\Requests\Request;
use Illuminate\Http\Client\Response;
use Generator;
use Illuminate\Support\LazyCollection;

class SearchCollection extends Request
{

    public function __construct(
        protected string  $query,
        protected bool    $includeAdult = false,
        protected int     $page = 1,
        protected ?string $region = null,
    )
    {
        parent::__construct();
    }

    public function send(): Response
    {
        return $this->request->get(
            '/search/collection',
            array_filter([
                'language' => $this->language ?? Tmdb::language(),
                'query' => $this->query,
                'include_adult' => $this->includeAdult,
                'page' => $this->page,
                'region' => $this->region,
            ], fn($value) => $value !== null)
        )->throw();
    }

    public function cursor(): LazyCollection
    {
        return LazyCollection::make(function (): Generator {
            $this->page = 1;
            do {
                $response = $this->send()->json();

                yield from $response['results'];

                $totalPages = $response['total_pages'];
                $this->page++;
            } while ($this->page <= $totalPages);
        });
    }
}