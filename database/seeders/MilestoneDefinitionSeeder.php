<?php

namespace Database\Seeders;

use App\Models\MilestoneDefinition;
use Illuminate\Database\Seeder;

class MilestoneDefinitionSeeder extends Seeder
{
    /**
     * General developmental milestones, loosely following CDC/WHO age bands.
     * Informational only — every baby develops at their own pace.
     */
    public function run(): void
    {
        if (MilestoneDefinition::query()->exists()) {
            return;
        }

        $milestones = [
            // 0-6 weeks
            ['age_min_weeks' => 0, 'age_max_weeks' => 6, 'category' => 'motor', 'title' => 'Lifts head briefly during tummy time', 'description' => 'Can lift and turn head slightly while lying on their stomach.'],
            ['age_min_weeks' => 0, 'age_max_weeks' => 6, 'category' => 'social', 'title' => 'Begins to calm to a familiar voice', 'description' => 'Quiets or becomes more alert when hearing a parent\'s voice.'],
            ['age_min_weeks' => 2, 'age_max_weeks' => 8, 'category' => 'social', 'title' => 'First social smile', 'description' => 'Smiles in response to your face or voice, not just reflexively.'],
            ['age_min_weeks' => 0, 'age_max_weeks' => 8, 'category' => 'cognitive', 'title' => 'Focuses on faces', 'description' => 'Can focus on a face about 8-12 inches away.'],

            // 6-12 weeks (1.5-3 months)
            ['age_min_weeks' => 6, 'age_max_weeks' => 14, 'category' => 'motor', 'title' => 'Holds head up steadily', 'description' => 'Head control improves; can hold head up when upright.'],
            ['age_min_weeks' => 8, 'age_max_weeks' => 16, 'category' => 'language', 'title' => 'Starts cooing', 'description' => 'Makes gurgling and cooing sounds to express contentment.'],
            ['age_min_weeks' => 8, 'age_max_weeks' => 16, 'category' => 'motor', 'title' => 'Pushes up on arms during tummy time', 'description' => 'Pushes chest up using arms while on their stomach.'],
            ['age_min_weeks' => 10, 'age_max_weeks' => 18, 'category' => 'cognitive', 'title' => 'Follows moving objects with eyes', 'description' => 'Tracks a toy or face as it moves side to side.'],

            // 3-4 months
            ['age_min_weeks' => 12, 'age_max_weeks' => 20, 'category' => 'motor', 'title' => 'Brings hands to midline / mouth', 'description' => 'Brings hands together and explores them with their mouth.'],
            ['age_min_weeks' => 14, 'age_max_weeks' => 22, 'category' => 'social', 'title' => 'Laughs out loud', 'description' => 'Begins to laugh in response to play.'],
            ['age_min_weeks' => 14, 'age_max_weeks' => 24, 'category' => 'motor', 'title' => 'Rolls from tummy to back', 'description' => 'First rolling milestone, usually front to back.'],

            // 4-6 months
            ['age_min_weeks' => 16, 'age_max_weeks' => 26, 'category' => 'motor', 'title' => 'Rolls from back to tummy', 'description' => 'Completes rolling in both directions.'],
            ['age_min_weeks' => 18, 'age_max_weeks' => 28, 'category' => 'motor', 'title' => 'Sits with support', 'description' => 'Can sit upright when propped or supported.'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'language', 'title' => 'Babbles with consonant sounds', 'description' => 'Starts babbling sounds like "ba", "da", "ma".'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'cognitive', 'title' => 'Reaches for and grabs objects', 'description' => 'Deliberately reaches out to grab nearby toys.'],

            // 6-9 months
            ['age_min_weeks' => 24, 'age_max_weeks' => 36, 'category' => 'motor', 'title' => 'Sits without support', 'description' => 'Sits independently without needing to be propped up.'],
            ['age_min_weeks' => 26, 'age_max_weeks' => 38, 'category' => 'cognitive', 'title' => 'Passes objects hand to hand', 'description' => 'Transfers a toy from one hand to the other.'],
            ['age_min_weeks' => 28, 'age_max_weeks' => 40, 'category' => 'social', 'title' => 'Shows stranger awareness', 'description' => 'May become clingy or wary of unfamiliar people.'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'motor', 'title' => 'Starts crawling', 'description' => 'Moves on hands and knees, or scoots to get around.'],
            ['age_min_weeks' => 32, 'age_max_weeks' => 44, 'category' => 'language', 'title' => 'Responds to their own name', 'description' => 'Turns or looks when their name is called.'],

            // 9-12 months
            ['age_min_weeks' => 36, 'age_max_weeks' => 50, 'category' => 'motor', 'title' => 'Pulls to stand', 'description' => 'Uses furniture to pull themselves up to standing.'],
            ['age_min_weeks' => 38, 'age_max_weeks' => 52, 'category' => 'cognitive', 'title' => 'Uses pincer grasp', 'description' => 'Picks up small items using thumb and forefinger.'],
            ['age_min_weeks' => 40, 'age_max_weeks' => 54, 'category' => 'social', 'title' => 'Waves bye-bye', 'description' => 'Imitates simple social gestures like waving.'],
            ['age_min_weeks' => 44, 'age_max_weeks' => 56, 'category' => 'language', 'title' => 'Says first word', 'description' => 'Uses a word like "mama" or "dada" with meaning.'],
            ['age_min_weeks' => 46, 'age_max_weeks' => 58, 'category' => 'motor', 'title' => 'Cruises along furniture', 'description' => 'Walks while holding onto furniture for support.'],

            // 12-18 months
            ['age_min_weeks' => 52, 'age_max_weeks' => 68, 'category' => 'motor', 'title' => 'Takes first independent steps', 'description' => 'Walks a few steps without support.'],
            ['age_min_weeks' => 56, 'age_max_weeks' => 72, 'category' => 'language', 'title' => 'Says several single words', 'description' => 'Vocabulary grows beyond the first word.'],
            ['age_min_weeks' => 60, 'age_max_weeks' => 76, 'category' => 'cognitive', 'title' => 'Points to ask for things or show interest', 'description' => 'Uses pointing to communicate wants or share attention.'],
            ['age_min_weeks' => 64, 'age_max_weeks' => 80, 'category' => 'social', 'title' => 'Imitates others during play', 'description' => 'Copies simple actions like clapping or feeding a doll.'],

            // 18-24 months
            ['age_min_weeks' => 72, 'age_max_weeks' => 90, 'category' => 'motor', 'title' => 'Runs and climbs', 'description' => 'Runs steadily and can climb onto low furniture.'],
            ['age_min_weeks' => 78, 'age_max_weeks' => 96, 'category' => 'language', 'title' => 'Combines two words', 'description' => 'Starts forming simple two-word phrases like "more milk".'],
            ['age_min_weeks' => 84, 'age_max_weeks' => 104, 'category' => 'cognitive', 'title' => 'Sorts shapes and colors', 'description' => 'Begins to match and sort simple shapes or colors.'],
            ['age_min_weeks' => 88, 'age_max_weeks' => 104, 'category' => 'social', 'title' => 'Shows independence', 'description' => 'Asserts preferences and may resist help with simple tasks.'],
        ];

        foreach ($milestones as $milestone) {
            MilestoneDefinition::create($milestone);
        }
    }
}
