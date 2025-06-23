<nav aria-label="Pagination">
    <hr class="my-0" />
    <ul class="pagination justify-content-center my-4">
        @php
            $query_params = [];
            if($search){
                $query_params[ 'search'] = $search;
            }else if($category){
                $query_params[ 'category']  = $category->id;
            }

            //default order is: created_at DESC (except search page, its sort by rating)
            //for older newer
            $start_index_page = 1;
            $last_index_page = $total_page;
            $link_older_newer = '';
            
            if($sort == 'asc'){
                $start_index_page = $total_page;
                $last_index_page = 1;
                if($search || $category){
                    $query_params['page'] = $start_index_page;
                    $query_params['sort'] = 'desc';
                    $link_older_newer = route('home', $query_params);
                }else{
                    $link_older_newer = route('home');
                }

            }else if($sort == 'desc'){
                $query_params['page'] = $last_index_page;
                $query_params['sort'] = 'asc';
                $link_older_newer = route('home', $query_params);
            }

            if($total_page > 5){
                //for "showed" pagination index
                /*
                *    (1) 2 3 4 5  ... 18   => on_first
                *    1 ..3 4 (5) 6 7.. 18   => on_middle
                *    1 .. 9 10 (11) 12 14 .. 18 => on_middle
                *    1 .. 14 15 (16) 17 18 => on_last
                */

                //Flags:
                global $on_middle, $on_first, $on_last, $leftmost_index_num, $rightmost_index_num, $link;
                $on_middle = false;
                $on_first = false;
                $on_last = false;

                $leftmost_index_num = null;
                $rightmost_index_num = null;

                if($sort == 'desc'){
                    //check if on first
                    if($current_page < 5){
                        $leftmost_index_num = 1;
                        $rightmost_index_num = 5;
                        $on_first = true;

                    //check if on middle
                    }else if($current_page >= 5 && $current_page < ($total_page - 3)){
                        $leftmost_index_num = $current_page - 2;
                        $rightmost_index_num = $current_page + 2;
                        $on_middle = true;

                    //check if on last
                    }else if($current_page >= $total_page - 3){
                        $leftmost_index_num = $total_page - 4;
                        $rightmost_index_num = $total_page;
                        $on_last = true;
                    }
                }else if($sort == 'asc'){
                    //check if on first
                    if($current_page > $start_index_page - 4){
                        $leftmost_index_num = $start_index_page;
                        $rightmost_index_num = $start_index_page - 4;
                        $on_first = true;

                    //check if on middle
                    }else if($current_page >= 5 && $current_page < ($total_page - 2)){
                        $leftmost_index_num = $current_page + 2;
                        $rightmost_index_num = $current_page - 2;
                        $on_middle = true;

                    //check if on last
                    }else if($current_page >= $last_index_page + 1 || $current_page == 1){
                        $leftmost_index_num = $last_index_page + 4;
                        $rightmost_index_num = $last_index_page;
                        $on_last = true;
                    }
                } 
            }
            
        @endphp

        @if(!$search)
            <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">@if($sort == 'desc') Lebih Baru @else Lebih Lama @endif</a></li>
        @endif

        @if($total_page <= 5)
            @php
                $condition = null;
                if($sort == 'desc'){
                    $condition = function($i) use($last_index_page){
                        return $i <= $last_index_page;
                    };

                }else if($sort == 'asc'){
                    $condition = function($i) use($last_index_page){
                        return $i >= $last_index_page;
                    };
                }
            @endphp
            @for($i = $start_index_page; $condition($i);)
                @php
                    $link = route('home');
                    if($i != 1 || $search || $category){
                        $query_params['page'] = $i;
                        $query_params['sort'] = $sort;
                        $link = route('home', $query_params);
                    }

                    $is_disable = false;
                    if($current_page == $i){
                        $is_disable = true;
                    }

                @endphp
                <li class="page-item @if($is_disable) disabled @endif"><a class="page-link" href="{{ $link }}">{{ $i }}</a></li> 
                @php
                    if($sort == 'desc'){
                        $i++;
                    }else if($sort == 'asc'){
                        $i--;
                    }
                @endphp
            @endfor
        @else
            @if($on_first)
                @php
                    $condition = null;
                    if($sort == 'desc'){
                        $condition = function($i) use($rightmost_index_num){
                            return $i <= $rightmost_index_num;
                        };

                    }else if($sort == 'asc'){
                        $condition = function($i) use($rightmost_index_num){
                            return $i >= $rightmost_index_num;
                        };
                    }
                @endphp
                @for($i = $leftmost_index_num; $condition($i);)
                    @php
                        $link = route('home');
                        if($i != 1){
                            $query_params['page'] = $i;
                            $query_params['sort'] = $sort;
                            $link = route('home', $query_params);
                        }

                        $is_disable = false;
                        if($current_page == $i){
                            $is_disable = true;
                        }

                    @endphp
                    <li class="page-item"><a class="page-link @if($is_disable) disabled @endif" href="{{ $link }}">{{ $i }}</a></li>
                    @php
                        if($sort == 'desc'){
                            $i++;
                        }else if($sort == 'asc'){
                            $i--;
                        }
                    @endphp
                @endfor

                    <li class="page-item disabled"><a class="page-link" href="#!">...</a></li>
                    @php
                        $query_params['page'] = $last_index_page;
                        $query_params['sort'] = $sort;
                        $link = route('home', $query_params);
                    @endphp
                    <li class="page-item"><a class="page-link" href="{{ $link }}">{{ $last_index_page }}</a></li>

            @elseif($on_middle)
                @php
                    $condition = null;
                    if($sort == 'desc'){
                        $condition = function($i) use($rightmost_index_num){
                            return $i <= $rightmost_index_num;
                        };

                    }else if($sort == 'asc'){
                        $condition = function($i) use($rightmost_index_num){
                            return $i >= $rightmost_index_num;
                        };
                    }
                @endphp
                @php
                        $query_params['page'] = $start_index_page;
                        $query_params['sort'] = $sort;
                        $link = route('home', $query_params);
                @endphp
                <li class="page-item" aria-current="page"><a class="page-link" href="{{ $link }}">{{ $start_index_page }}</a></li>
                <li class="page-item disabled"><a class="page-link" href="#!">...</a></li>

                @for($i = $leftmost_index_num; $condition($i);)
                    @php
                        $link = route('home');
                        if($i != 1){
                            $query_params['page'] = $i;
                            $query_params['sort'] = $sort;
                            $link = route('home', $query_params);
                        }

                        $is_disable = false;
                        if($current_page == $i){
                            $is_disable = true;
                        }

                    @endphp
                    <li class="page-item"><a class="page-link @if($is_disable) disabled @endif" href="{{ $link }}">{{ $i }}</a></li>
                    @php
                        if($sort == 'desc'){
                            $i++;
                        }else if($sort == 'asc'){
                            $i--;
                        }
                    @endphp
                @endfor

                @if($on_middle || $on_first)
                    <li class="page-item disabled"><a class="page-link" href="#!">...</a></li>
                @endif

                @php
                    $query_params['page'] = $last_index_page;
                    $query_params['sort'] = $sort;
                    $link = route('home', $query_params);
                @endphp
                <li class="page-item"><a class="page-link" href="{{ $link }}">{{ $last_index_page }}</a></li>

            @elseif($on_last)
                @php
                    $condition = null;
                    if($sort == 'desc'){
                        $condition = function($i) use($rightmost_index_num){
                            return $i <= $rightmost_index_num;
                        };

                    }else if($sort == 'asc'){
                        $condition = function($i) use($rightmost_index_num){
                            return $i >= $rightmost_index_num;
                        };
                    }
                @endphp
                @php
                    $query_params['page'] = $start_index_page;
                    $query_params['sort'] = $sort;
                    $link = route('home', $query_params);
                @endphp
                <li class="page-item"><a class="page-link" href="{{ $link }}">{{ $start_index_page }}</a></li>
                <li class="page-item disabled"><a class="page-link" href="#!">...</a></li>

                @for($i = $leftmost_index_num; $condition($i);)
                    @php
                        $link = '';
                        if($sort == 'asc'){
                            $query_params['page'] = $i;
                            $query_params['sort'] = $sort;
                            $link = route('home', $query_params);
                        }else if($sort == 'desc'){
                            if($i != 1){
                                $query_params['page'] = $i;
                                $query_params['sort'] = $sort;
                                $link = route('home', $query_params);
                            }
                        }


                        $is_disable = false;
                        if($current_page == $i){
                            $is_disable = true;
                        }

                    @endphp
                    <li class="page-item"><a class="page-link @if($is_disable) disabled @endif" href="{{ $link }}">{{ $i }}</a></li>

                    @php
                        if($sort == 'desc'){
                            $i++;
                        }else if($sort == 'asc'){
                            $i--;
                        }
                    @endphp
                @endfor
            @endif
        @endif
        @if(!$search)
            <li class="page-item"><a class="page-link @if($total_page == 1) disabled @endif" href="{{ $link_older_newer }}" tabindex="-1" aria-disabled="true">@if($sort == 'desc') Lebih Lama @else Lebih Baru @endif</a></li>
        @endif
    </ul>
</nav>
