<?php

namespace Tests\Feature;

use App\Models\Baby;
use App\Models\BabyPhoto;
use App\Models\BabyStory;
use App\Models\DiaperEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Volt;
use Tests\TestCase;

class BabyTrackerFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_empty_state_with_no_babies(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('add your baby');
    }

    public function test_dashboard_renders_with_feed_and_diaper_trend_charts(): void
    {
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $baby->diaperEntries()->create(['is_wet' => true, 'is_dirty' => false, 'occurred_at' => now()]);
        $baby->diaperEntries()->create(['is_wet' => false, 'is_dirty' => true, 'occurred_at' => now()->subDay()]);
        $baby->feedEntries()->create(['type' => 'bottle', 'fed_at' => now(), 'amount_oz' => 3]);

        session(['current_baby_id' => $baby->id]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Diaper Activity')
            ->assertSee('Feeding Activity')
            ->assertSee($baby->name);
    }

    public function test_dashboard_chart_week_navigation_moves_the_trend_window(): void
    {
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $baby->diaperEntries()->create(['is_wet' => true, 'is_dirty' => false, 'occurred_at' => now()]);
        $baby->diaperEntries()->create(['is_wet' => false, 'is_dirty' => true, 'occurred_at' => now()->subWeek()]);

        session(['current_baby_id' => $baby->id]);
        $this->actingAs($user);

        $component = Volt::test('dashboard');

        $thisWeek = $component->get('diaperTrend');
        $this->assertSame(1, $thisWeek->pluck('pee')->sum());
        $this->assertSame(0, $thisWeek->pluck('poop')->sum());

        $component->call('prevChartWeek');

        $lastWeek = $component->get('diaperTrend');
        $this->assertSame(0, $lastWeek->pluck('pee')->sum());
        $this->assertSame(1, $lastWeek->pluck('poop')->sum());

        // Can't navigate past the current week.
        $component->call('nextChartWeek')->call('nextChartWeek');
        $this->assertSame(0, $component->get('chartWeekOffset'));
    }

    public function test_dashboard_last_feed_and_diaper_cards_link_to_full_lists(): void
    {
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();
        session(['current_baby_id' => $baby->id]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee(route('babies.feeds.index', $baby), false)
            ->assertSee(route('babies.diapers.index', $baby), false);
    }

    public function test_feed_and_diaper_lists_group_entries_by_date(): void
    {
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $baby->feedEntries()->create(['type' => 'bottle', 'fed_at' => now(), 'amount_oz' => 4]);
        $baby->feedEntries()->create(['type' => 'bottle', 'fed_at' => now()->subDay(), 'amount_oz' => 3]);
        $baby->diaperEntries()->create(['is_wet' => true, 'is_dirty' => false, 'occurred_at' => now()]);
        $baby->diaperEntries()->create(['is_wet' => false, 'is_dirty' => true, 'occurred_at' => now()->subDay()]);

        $this->actingAs($user);

        $this->get(route('babies.feeds.index', $baby))
            ->assertOk()
            ->assertSeeInOrder(['Today', 'Yesterday']);

        $this->get(route('babies.diapers.index', $baby))
            ->assertOk()
            ->assertSeeInOrder(['Today', 'Yesterday']);
    }

    public function test_dashboard_quick_add_feed_and_diaper_save_notes(): void
    {
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();
        session(['current_baby_id' => $baby->id]);

        $this->actingAs($user);

        Volt::test('dashboard')
            ->set('quickFeedType', 'bottle')
            ->set('quickFeedAt', now()->format('Y-m-d\TH:i'))
            ->set('quickFeedAmount', 4)
            ->set('quickFeedNotes', 'Spit up a little after')
            ->call('saveFeed');

        $this->assertDatabaseHas('feed_entries', [
            'baby_id' => $baby->id,
            'notes' => 'Spit up a little after',
        ]);

        Volt::test('dashboard')
            ->set('quickDiaperIsWet', true)
            ->set('quickDiaperIsDirty', true)
            ->set('quickDiaperConsistency', 'soft')
            ->set('quickDiaperAt', now()->format('Y-m-d\TH:i'))
            ->set('quickDiaperNotes', 'Diaper rash cream applied')
            ->call('saveDiaper');

        $this->assertDatabaseHas('diaper_entries', [
            'baby_id' => $baby->id,
            'consistency' => 'soft',
            'notes' => 'Diaper rash cream applied',
        ]);
    }

    public function test_dashboard_quick_add_only_closes_the_modal_on_actual_success(): void
    {
        // The modal used to close via a client-side .then() on the Livewire
        // call, which fires even when the server-side save silently failed
        // validation (e.g. neither pee nor poop checked) — the request still
        // resolves normally, so the modal closed and hid the error message.
        // Closing is now an explicit server-side dispatch on the success
        // path only.
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();
        session(['current_baby_id' => $baby->id]);

        $this->actingAs($user);

        Volt::test('dashboard')
            ->set('quickFeedType', 'bottle')
            ->set('quickFeedAt', now()->format('Y-m-d\TH:i'))
            ->set('quickFeedAmount', 4)
            ->call('saveFeed')
            ->assertDispatched('close-modal', 'quick-feed');

        Volt::test('dashboard')
            ->set('quickDiaperIsWet', false)
            ->set('quickDiaperIsDirty', false)
            ->set('quickDiaperAt', now()->format('Y-m-d\TH:i'))
            ->call('saveDiaper')
            ->assertHasErrors('quickDiaperIsWet')
            ->assertNotDispatched('close-modal');

        Volt::test('dashboard')
            ->set('quickDiaperIsWet', true)
            ->set('quickDiaperAt', now()->format('Y-m-d\TH:i'))
            ->call('saveDiaper')
            ->assertDispatched('close-modal', 'quick-diaper');
    }

    public function test_dashboard_quick_add_saves_the_exact_wall_clock_time_entered(): void
    {
        // The app's default timezone (config('app.timezone')) is set to the
        // family's real timezone, so a datetime-local input's wall-clock value
        // can be saved as-is with no UTC conversion — converting it would
        // shift it by the local/UTC offset and produce the wrong instant.
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();
        session(['current_baby_id' => $baby->id]);

        $this->actingAs($user);

        $enteredAt = '2026-01-15T10:30';

        Volt::test('dashboard')
            ->set('quickFeedType', 'bottle')
            ->set('quickFeedAmount', 4)
            ->set('quickFeedAt', $enteredAt)
            ->call('saveFeed');

        $this->assertDatabaseHas('feed_entries', [
            'baby_id' => $baby->id,
            'fed_at' => '2026-01-15 10:30:00',
        ]);

        Volt::test('dashboard')
            ->set('quickDiaperIsWet', true)
            ->set('quickDiaperAt', $enteredAt)
            ->call('saveDiaper');

        $this->assertDatabaseHas('diaper_entries', [
            'baby_id' => $baby->id,
            'occurred_at' => '2026-01-15 10:30:00',
        ]);
    }

    public function test_user_can_create_a_baby(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('babies.store'), [
            'name' => 'Test Baby',
            'sex' => 'female',
            'date_of_birth' => now()->subMonths(4)->format('Y-m-d'),
        ]);

        $baby = Baby::first();
        $response->assertRedirect(route('babies.show', $baby));
        $this->assertSame($user->id, $baby->user_id);
    }

    public function test_user_cannot_view_another_users_baby(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $baby = Baby::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->get(route('babies.show', $baby))
            ->assertForbidden();
    }

    public function test_user_can_log_a_weight_feed_diaper_sleep_and_milestone(): void
    {
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $this->actingAs($user);

        $this->post(route('babies.weights.store', $baby), [
            'weight_kg' => 5.4,
            'measured_at' => now(),
        ])->assertRedirect(route('babies.weights.index', $baby));

        $this->post(route('babies.feeds.store', $baby), [
            'type' => 'bottle',
            'fed_at' => now(),
            'amount_oz' => 4.5,
        ])->assertRedirect(route('babies.feeds.index', $baby));

        $this->post(route('babies.diapers.store', $baby), [
            'is_wet' => '1',
            'occurred_at' => now(),
        ])->assertRedirect(route('babies.diapers.index', $baby));

        $this->post(route('babies.sleeps.store', $baby), [
            'started_at' => now()->subHour(),
            'ended_at' => now(),
        ])->assertRedirect(route('babies.sleeps.index', $baby));

        $this->post(route('babies.milestones.store', $baby), [
            'title' => 'First smile',
            'achieved_on' => now()->format('Y-m-d'),
        ])->assertRedirect(route('babies.milestones.index', $baby));

        $this->assertDatabaseCount('weight_entries', 1);
        $this->assertDatabaseCount('feed_entries', 1);
        $this->assertDatabaseCount('diaper_entries', 1);
        $this->assertDatabaseCount('sleep_entries', 1);
        $this->assertDatabaseCount('milestone_entries', 1);

        $this->get(route('babies.show', $baby))->assertOk()->assertSee('First smile');
    }

    public function test_diaper_entry_requires_at_least_one_of_pee_or_poop(): void
    {
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $this->actingAs($user)->post(route('babies.diapers.store', $baby), [
            'occurred_at' => now(),
        ])->assertSessionHasErrors('is_wet');

        $this->assertDatabaseCount('diaper_entries', 0);

        $this->post(route('babies.diapers.store', $baby), [
            'is_wet' => '1',
            'is_dirty' => '1',
            'consistency' => 'soft',
            'occurred_at' => now(),
        ])->assertRedirect(route('babies.diapers.index', $baby));

        $entry = DiaperEntry::first();
        $this->assertTrue($entry->is_wet);
        $this->assertTrue($entry->is_dirty);
        $this->assertSame('Pee & Poop', $entry->label());
    }

    public function test_seeded_guide_and_milestone_content_shows_on_guide_page(): void
    {
        $this->seed();
        $this->seed(); // seeders must be safe to re-run (e.g. on every container boot)

        $this->assertGreaterThan(0, \App\Models\AgeGuide::count());
        $this->assertGreaterThan(0, \App\Models\MilestoneDefinition::count());

        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create(['date_of_birth' => now()->subWeeks(8)]);

        $this->actingAs($user)
            ->get(route('babies.guide', $baby))
            ->assertOk()
            ->assertDontSee('No guide content for this age yet')
            ->assertDontSee('No milestones catalogued for this age range');
    }

    public function test_growth_chart_and_guide_pages_render(): void
    {
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create(['date_of_birth' => now()->subMonths(6)]);

        $baby->weightEntries()->create(['weight_kg' => 5.0, 'measured_at' => now()->subMonths(2)]);
        $baby->weightEntries()->create(['weight_kg' => 6.2, 'measured_at' => now()->subMonth()]);

        $this->actingAs($user);

        $this->get(route('babies.growth', $baby))->assertOk()->assertSee('Weight over time');
        $this->get(route('babies.guide', $baby))->assertOk();
        $this->get(route('babies.age', $baby))->assertOk()->assertJsonStructure(['days', 'weeks', 'months', 'years', 'label']);
    }

    public function test_user_can_save_pediatrician_info(): void
    {
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $this->actingAs($user)->put(route('babies.pediatrician.update', $baby), [
            'doctor_name' => 'Dr. Smith',
            'clinic_name' => 'Sunshine Pediatrics',
        ])->assertRedirect(route('babies.pediatrician.edit', $baby));

        $this->assertDatabaseHas('pediatricians', [
            'baby_id' => $baby->id,
            'doctor_name' => 'Dr. Smith',
        ]);
    }

    public function test_explicit_taken_at_takes_priority_over_exif_and_defaults(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $this->actingAs($user)->post(route('babies.photos.store', $baby), [
            'photo' => UploadedFile::fake()->image('baby.jpg'),
            'taken_at' => '2020-05-01',
        ])->assertRedirect(route('babies.photos.index', $baby));

        $photo = $baby->photos()->first();
        $this->assertSame('2020-05-01', $photo->taken_at->format('Y-m-d'));
    }

    public function test_upload_without_taken_at_or_exif_falls_back_to_now(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $this->actingAs($user)->post(route('babies.photos.store', $baby), [
            'photo' => UploadedFile::fake()->image('baby.jpg'),
        ])->assertRedirect(route('babies.photos.index', $baby));

        $photo = $baby->photos()->first();
        $this->assertTrue($photo->taken_at->isToday());
    }

    public function test_user_can_upload_several_photos_one_request_at_a_time(): void
    {
        // Selecting multiple files uploads them as separate sequential
        // requests (one small POST per photo) rather than one large
        // multi-file POST, to avoid hitting post_max_size on the server.
        Storage::fake('public');

        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $this->actingAs($user);

        foreach (['one.jpg', 'two.jpg', 'three.jpg'] as $name) {
            $this->postJson(route('babies.photos.store', $baby), [
                'photo' => UploadedFile::fake()->image($name),
            ])->assertOk();
        }

        $this->assertSame(3, $baby->photos()->count());
        $baby->photos()->get()->each(fn ($photo) => Storage::disk('public')->assertExists($photo->path));
    }

    public function test_photo_gallery_paginates_large_libraries(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        BabyPhoto::factory()->for($baby)->count(75)->create();

        $response = $this->actingAs($user)->get(route('babies.photos.index', $baby))->assertOk();

        $this->assertCount(60, $response->viewData('photos'));
        $response->assertSee('Next');
    }

    public function test_user_can_upload_and_set_profile_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $this->actingAs($user)->post(route('babies.photos.store', $baby), [
            'photo' => UploadedFile::fake()->image('baby.jpg'),
        ])->assertRedirect(route('babies.photos.index', $baby));

        $photo = $baby->photos()->first();
        $this->assertNotNull($photo);
        Storage::disk('public')->assertExists($photo->path);

        $this->patch(route('babies.photos.profile', [$baby, $photo]))
            ->assertRedirect(route('babies.photos.index', $baby));

        $this->assertTrue($photo->fresh()->is_profile);
        $this->assertSame($photo->path, $baby->fresh()->profile_photo_path);
    }

    public function test_quick_avatar_upload_sets_profile_photo_in_one_step(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $this->actingAs($user)->post(route('babies.photos.store', $baby), [
            'photo' => UploadedFile::fake()->image('avatar.jpg'),
            'set_as_profile' => '1',
        ])->assertRedirect(route('babies.photos.index', $baby));

        $photo = $baby->photos()->first();
        $this->assertTrue($photo->is_profile);
        $this->assertSame($photo->path, $baby->fresh()->profile_photo_path);
    }

    public function test_feed_entry_formats_amount_by_trimming_trailing_zeros(): void
    {
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $whole = $baby->feedEntries()->create(['type' => 'bottle', 'fed_at' => now(), 'amount_oz' => 2]);
        $fractional = $baby->feedEntries()->create(['type' => 'bottle', 'fed_at' => now(), 'amount_oz' => 3.5]);
        $none = $baby->feedEntries()->create(['type' => 'breast', 'fed_at' => now()]);

        $this->assertSame('2oz', $whole->formattedAmount());
        $this->assertSame('3.5oz', $fractional->formattedAmount());
        $this->assertNull($none->formattedAmount());
    }

    public function test_user_can_create_a_story_with_caption_only(): void
    {
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $this->actingAs($user)->post(route('babies.stories.store', $baby), [
            'caption' => 'First time rolling over!',
        ])->assertRedirect(route('babies.stories.index', $baby));

        $this->assertDatabaseHas('baby_stories', [
            'baby_id' => $baby->id,
            'caption' => 'First time rolling over!',
            'media_path' => null,
        ]);
    }

    public function test_user_can_create_a_story_with_image_only(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $this->actingAs($user)->post(route('babies.stories.store', $baby), [
            'media' => UploadedFile::fake()->image('moment.jpg'),
        ])->assertRedirect(route('babies.stories.index', $baby));

        $story = $baby->storyEntries()->first();
        $this->assertNotNull($story);
        $this->assertNull($story->caption);
        $this->assertSame('image', $story->media_type);
        Storage::disk('public')->assertExists($story->media_path);
    }

    public function test_user_can_create_a_story_with_video_only(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $this->actingAs($user)->post(route('babies.stories.store', $baby), [
            'media' => UploadedFile::fake()->create('moment.mp4', 5000, 'video/mp4'),
        ])->assertRedirect(route('babies.stories.index', $baby));

        $story = $baby->storyEntries()->first();
        $this->assertNotNull($story);
        $this->assertTrue($story->isVideo());
        Storage::disk('public')->assertExists($story->media_path);
    }

    public function test_story_requires_a_caption_or_media(): void
    {
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $this->actingAs($user)->post(route('babies.stories.store', $baby), [])
            ->assertSessionHasErrors(['caption', 'media']);

        $this->assertSame(0, $baby->storyEntries()->count());
    }

    public function test_stories_feed_groups_by_date_and_paginates(): void
    {
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        BabyStory::factory()->for($baby)->count(25)->create(['occurred_at' => now()]);

        $response = $this->actingAs($user)->get(route('babies.stories.index', $baby))->assertOk();

        $this->assertCount(20, $response->viewData('stories'));
    }

    public function test_user_can_update_and_delete_a_story(): void
    {
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();
        $story = BabyStory::factory()->for($baby)->create(['caption' => 'Original']);

        $this->actingAs($user)->put(route('babies.stories.update', [$baby, $story]), [
            'caption' => 'Updated caption',
            'occurred_at' => now()->format('Y-m-d\TH:i'),
        ])->assertRedirect(route('babies.stories.index', $baby));

        $this->assertSame('Updated caption', $story->fresh()->caption);

        $this->delete(route('babies.stories.destroy', [$baby, $story]))
            ->assertRedirect(route('babies.stories.index', $baby));

        $this->assertDatabaseMissing('baby_stories', ['id' => $story->id]);
    }

    public function test_csv_import_for_diapers_and_bottle_feeds(): void
    {
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $this->actingAs($user);

        $diaperCsv = "occurred_at,pee,poop\n2026-08-01 08:30,1,0\n2026-08-01 12:15,1,1\n2026-08-01 14:00,0,0\n";
        $this->post(route('babies.import.diapers', $baby), [
            'file' => UploadedFile::fake()->createWithContent('diapers.csv', $diaperCsv),
        ])->assertRedirect(route('babies.import.show', $baby));

        $this->assertDatabaseCount('diaper_entries', 2);
        $this->assertDatabaseHas('diaper_entries', ['is_wet' => 1, 'is_dirty' => 1]);

        $feedCsv = "fed_at,amount_oz\n2026-08-01 09:00,3.5\n2026-08-01 13:00,4\n";
        $this->post(route('babies.import.feeds', $baby), [
            'file' => UploadedFile::fake()->createWithContent('feeds.csv', $feedCsv),
        ])->assertRedirect(route('babies.import.show', $baby));

        $this->assertDatabaseCount('feed_entries', 2);
        $this->assertDatabaseHas('feed_entries', ['type' => 'bottle', 'amount_oz' => 3.5]);
    }

    public function test_csv_import_handles_blank_consistency_cells(): void
    {
        // Regression test: a blank CSV cell parses to an empty string, not
        // null. The `consistency` column is a strict enum (soft/firm/runny/
        // hard or NULL) — "" satisfies neither, so every row with a blank
        // consistency used to throw and get silently counted as "skipped".
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $this->actingAs($user);

        $csv = "occurred_at,pee,poop,consistency,notes\n"
            ."2026-08-01 08:30,1,0,,\n"
            ."2026-08-01 12:15,0,1,hard,\n"
            ."2026-08-01 14:00,1,0,,\n";

        $this->post(route('babies.import.diapers', $baby), [
            'file' => UploadedFile::fake()->createWithContent('diapers.csv', $csv),
        ])->assertRedirect(route('babies.import.show', $baby));

        $this->assertDatabaseCount('diaper_entries', 3);
        $this->assertDatabaseHas('diaper_entries', ['is_wet' => 1, 'consistency' => null]);
        $this->assertDatabaseHas('diaper_entries', ['is_dirty' => 1, 'consistency' => 'hard']);
    }

    public function test_all_edit_and_index_pages_render_for_a_fully_populated_baby(): void
    {
        $user = User::factory()->create();
        $baby = Baby::factory()->for($user)->create();

        $weight = $baby->weightEntries()->create(['weight_kg' => 5.4, 'measured_at' => now()]);
        $feed = $baby->feedEntries()->create(['type' => 'bottle', 'fed_at' => now(), 'amount_oz' => 3.5]);
        $diaper = $baby->diaperEntries()->create(['is_wet' => true, 'occurred_at' => now()]);
        $sleep = $baby->sleepEntries()->create(['started_at' => now()->subHour(), 'ended_at' => now()]);
        $milestone = $baby->milestoneEntries()->create(['title' => 'Rolled over', 'achieved_on' => now()]);
        $story = $baby->storyEntries()->create(['caption' => 'A moment', 'occurred_at' => now()]);

        $this->actingAs($user);

        $this->get(route('babies.edit', $baby))->assertOk();
        $this->get(route('babies.weights.edit', [$baby, $weight]))->assertOk();
        $this->get(route('babies.feeds.edit', [$baby, $feed]))->assertOk();
        $this->get(route('babies.diapers.edit', [$baby, $diaper]))->assertOk();
        $this->get(route('babies.sleeps.edit', [$baby, $sleep]))->assertOk();
        $this->get(route('babies.milestones.edit', [$baby, $milestone]))->assertOk();
        $this->get(route('babies.milestones.index', $baby))->assertOk();
        $this->get(route('babies.pediatrician.edit', $baby))->assertOk();
        $this->get(route('babies.photos.index', $baby))->assertOk();
        $this->get(route('babies.stories.index', $baby))->assertOk();
        $this->get(route('babies.stories.edit', [$baby, $story]))->assertOk();
        $this->get(route('babies.import.show', $baby))->assertOk();
        $this->get(route('babies.index'))->assertOk();
    }
}
