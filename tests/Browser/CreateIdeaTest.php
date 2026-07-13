<?php
use App\Models\User;
use App\Models\Idea;

it('does something', function (){
    $this->actingAs(User::factory()->created());


    visit('/ideas')
        ->click('@create-idea-button')
        ->fill('title', 'Some Example Title')
        ->click('@button-status-completed')
        ->fill('description', 'An example description')
        ->click('Create')
        ->asserPathIs('/ideas');

    expect($user->ideas()->first())->toMatchArray([
        'title' => 'Some Example Title',
        'status' => 'completed',
        'description' => 'An example description',
    ])
});

