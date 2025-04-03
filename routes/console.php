<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('repostea:promote-links')
    ->hourly()
    ->description('Automatically promote links that meet the criteria')
    ->when(fn () => config('schedule.promote_links_enabled'));

Schedule::command('repostea:update-karma')
    ->dailyAt('03:00')
    ->description('Update karma for all users')
    ->when(fn () => config('schedule.update_karma_enabled'));

Schedule::command('migrate:fresh --seed --force')
    ->dailyAt('05:00')
    ->description('Reset DB and seed data')
    ->when(fn () => app()->environment('staging')
        && config('schedule.daily_reset_enabled') === true
        && config('schedule.daily_reset_confirmed') === 'yes_i_know'
    );
