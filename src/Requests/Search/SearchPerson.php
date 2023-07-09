<?php

namespace Astrotomic\Tmdb\Requests\Search;

use Astrotomic\Tmdb\Facades\Tmdb;
use Astrotomic\Tmdb\Requests\Request;
use Generator;
use Illuminate\Http\Client\Response;
use Illuminate\Support\LazyCollection;

class SearchPerson extends Request
{
    public function __construct(
        protected string $query,
        protected bool   $includeAdult = false,
        protected int    $page = 1,
    )
    {
        parent::__construct();
    }

    public function send(): Response
    {
        return $this->request->get(
            '/search/person',
            array_filter([
                'language' => $this->language ?? Tmdb::language(),
                'query' => $this->query,
                'page' => $this->page,
                'include_adult' => $this->includeAdult ? 'true' : 'false',
            ])
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