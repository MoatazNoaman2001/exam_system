@extends('layouts.app')

@section('title', __('lang.leaderboard'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/LeaderBoard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <div class="leaderboard" >
        <div class="leaderboard__inner-frame">
            <!-- Page Title -->
            <div class="leaderboard__page-title">
                <div class="leaderboard__inner-frame2">
                    <div class="leaderboard__top-frame">
                        <div class="leaderboard__title">{{ __('lang.leaderboard') }}</div>
                        <div class="leaderboard__arrow-right">
                            <img class="leaderboard__vuesax-outline-arrow-right" src="{{ asset('images/vuesax-outline-arrow-right.svg') }}" alt="{{ __('lang.arrow_right') }}" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="leaderboard__tab-leaderboard">
                <div class="leaderboard__tab">
                    <div class="leaderboard__tab-text">{{ __('lang.all_time') }}</div>
                </div>
                <div class="leaderboard__tab leaderboard__tab--active">
                    <div class="leaderboard__tab-text">{{ __('lang.this_week') }}</div>
                </div>
            </div>

            <!-- User Rank -->
            <div class="leaderboard__user-rank">
                <div class="leaderboard__user-rank-frame">
                    <div class="leaderboard__user-rank-text">
                        {{ __('lang.you_are_better_than', ['percent' => $progress->top_users_percent ?? '10%']) }}
                    </div>
                    <div class="leaderboard__user-rank-number">#{{ $userRank }}</div>
                </div>
            </div>

            <!-- Top Users -->
            <div class="leaderboard__users-rank">
                @foreach($topUsers as $index => $topUser)
                    <div class="leaderboard__user-frame {{ $topUser->id == $user->id ? 'leaderboard__user-frame--current' : '' }}">
                        <div class="leaderboard__user-info-frame">
                            <div class="leaderboard__rank-number">{{ $index + 1 }}</div>
                            <div class="leaderboard__user-info-container">
                                <img class="leaderboard__avatar" src="{{ $topUser->image ? asset('storage/avatars/' . $topUser->image) : asset('images/person_placeholder.png') }}" alt="{{ __('lang.avatar') }}" />
                                <div class="leaderboard__user-info">
                                    <div class="leaderboard__greeting-container">
                                        <div class="leaderboard__greeting-text">{{ $topUser->username }}</div>
                                    </div>
                                    <div class="leaderboard__question-text">
                                        {{ number_format($topUser->points) }} {{ __('lang.points') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="leaderboard__medal">
                            @if($index < 3)
                                <img class="leaderboard__star" src="{{ asset('images/Medal.png') }}" alt="{{ __('lang.medal') }}" />
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Toggle between tabs
            const tabs = document.querySelectorAll('.leaderboard__tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('leaderboard__tab--active'));
                    tab.classList.add('leaderboard__tab--active');
                    // Add logic to update data based on tab (all_time or this_week)
                });
            });
        });
    </script>
@endsection