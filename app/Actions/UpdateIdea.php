<?php

namespace App\Actions;

use App\Models\Idea;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateIdea
{
    public function handle(array $attributes, Idea $idea): Idea
    {
        $data = collect($attributes)
            ->only([
                'title',
                'description',
                'status',
                'links',
            ])
            ->toArray();

        $oldImagePath = null;

        if (! empty($attributes['image'])) {
            $oldImagePath = $idea->image_path;

            $data['image_path'] = $attributes['image']
                ->store('ideas', 'public');
        }

        $steps = collect($attributes['steps'] ?? [])
            ->map(function (array $step) {
                return [
                    'description' => $step['description'],
                    'completed' => (bool) ($step['completed'] ?? false),
                ];
            })
            ->values()
            ->all();

        DB::transaction(function () use ($idea, $data, $steps) {
            $idea->update($data);

            $idea->steps()->delete();

            $idea->steps()->createMany($steps);
        });

        if ($oldImagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return $idea->refresh();
    }
}
