<?php

namespace Astrotomic\Tmdb\Requests\Search;

use Astrotomic\Tmdb\Facades\Tmdb;
use Astrotomic\Tmdb\Requests\Request;
use Generator;
use Illuminate\Http\Client\Response;
use Illuminate\Support\LazyCollection;

class SearchTV extends Request
{
    public function __construct(
        protected string  $query,
        protected bool    $includeAdult = false,
        protected ?string $first_air_date_year = null,
        protected int     $page = 1,
        protected ?string $year = null
    )
    {
        parent::__construct();
    }

    public function send(): Response
    {
        return $this->request->get(
            '/search/tv',
            array_filter([
                'language' => $this->language ?? Tmdb::language(),
                'query' => $this->query,
                'include_adult' => $this->includeAdult ? 'true' : 'false',
                'primary_release_year' => $this->first_air_date_year,
                'page' => $this->page,
                'year' => $this->year,
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