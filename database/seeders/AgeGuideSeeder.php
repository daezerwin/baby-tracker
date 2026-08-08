<?php

namespace Database\Seeders;

use App\Models\AgeGuide;
use Illuminate\Database\Seeder;

class AgeGuideSeeder extends Seeder
{
    /**
     * General, non-medical guidance by age band — biweekly for the first
     * 12 weeks, then monthly through 24 months. Always defer to your
     * pediatrician for anything specific to your baby.
     */
    public function run(): void
    {
        if (AgeGuide::query()->exists()) {
            return;
        }

        $guides = [
            [
                'age_label' => 'Weeks 0-2 (Newborn)',
                'age_min_weeks' => 0, 'age_max_weeks' => 2,
                'weekly_goals' => 'Focus on feeding, bonding, and recovery. Aim for 8-12 feeds a day and skin-to-skin contact.',
                'feeding_tips' => 'Feed on demand (every 2-3 hours). Watch for hunger cues like rooting and hand-to-mouth movements rather than waiting for crying.',
                'sleep_tips' => 'Newborns sleep 14-17 hours a day in short stretches. Always place baby on their back on a firm, flat surface to sleep.',
                'development_tips' => 'Vision is blurry beyond 8-12 inches. Talk and sing often — hearing is fully developed at birth.',
                'safety_tips' => 'Support the head and neck at all times. Keep the crib free of blankets, pillows, and toys.',
            ],
            [
                'age_label' => 'Weeks 2-4',
                'age_min_weeks' => 2, 'age_max_weeks' => 4,
                'weekly_goals' => 'Establish a loose feeding rhythm and start noticing early alert periods for interaction.',
                'feeding_tips' => 'Growth spurts around 2-3 weeks may increase feeding frequency temporarily — this is normal.',
                'sleep_tips' => 'Begin a simple wind-down routine (dim lights, quiet voice) to gently cue nighttime sleep.',
                'development_tips' => 'Baby may start briefly following a face or bright object with their eyes.',
                'safety_tips' => 'Never leave baby unattended on a raised surface. Check car seat installation is correct.',
            ],
            [
                'age_label' => 'Weeks 4-6',
                'age_min_weeks' => 4, 'age_max_weeks' => 6,
                'weekly_goals' => 'Start short daily tummy time sessions (1-2 minutes, a few times a day) to build neck strength.',
                'feeding_tips' => 'Feeds may start to space out slightly to every 2.5-3.5 hours.',
                'sleep_tips' => 'One slightly longer sleep stretch may start to emerge, often earlier in the night.',
                'development_tips' => 'Watch for the first social smile — a big emotional milestone for many parents.',
                'safety_tips' => 'Keep small objects, pet hazards, and cords well out of reach as baby becomes more alert.',
            ],
            [
                'age_label' => 'Weeks 6-8',
                'age_min_weeks' => 6, 'age_max_weeks' => 8,
                'weekly_goals' => 'Increase tummy time gradually; aim for a few short sessions spread through the day.',
                'feeding_tips' => 'If bottle feeding, continue paced feeding to help baby self-regulate intake.',
                'sleep_tips' => 'Daytime naps may start to organize into 3-4 shorter naps rather than constant dozing.',
                'development_tips' => 'Cooing sounds often begin — respond by talking back to encourage early language.',
                'safety_tips' => 'Schedule/attend the 6-8 week wellness check-up and routine vaccinations if due.',
            ],
            [
                'age_label' => 'Weeks 8-10',
                'age_min_weeks' => 8, 'age_max_weeks' => 10,
                'weekly_goals' => 'Encourage reaching and batting at toys during awake time.',
                'feeding_tips' => 'Continue on-demand feeding; appetite may increase with more active awake periods.',
                'sleep_tips' => 'Keep sleep space consistent — same crib/bassinet helps sleep associations form.',
                'development_tips' => 'Head control is improving; baby may hold their head steady briefly when upright.',
                'safety_tips' => 'Re-check smoke and carbon monoxide detectors as baby spends more time in different rooms.',
            ],
            [
                'age_label' => 'Weeks 10-12',
                'age_min_weeks' => 10, 'age_max_weeks' => 12,
                'weekly_goals' => 'Introduce a simple, repeatable daily rhythm (wake, feed, play, sleep) rather than a strict schedule.',
                'feeding_tips' => 'Feeding sessions may become more efficient and slightly shorter as baby gets stronger.',
                'sleep_tips' => 'A longer nighttime stretch (4-6 hours) often starts to appear around this age for some babies.',
                'development_tips' => 'Babbling and more varied vocal sounds continue to develop — keep narrating your day to them.',
                'safety_tips' => 'Baby-proof surfaces they may soon roll toward; avoid leaving them unattended on beds or sofas.',
            ],
            [
                'age_label' => '3 Months',
                'age_min_weeks' => 12, 'age_max_weeks' => 16,
                'weekly_goals' => 'Work on tummy time until baby can push up on their forearms comfortably.',
                'feeding_tips' => 'Feeding volumes grow steadily; watch for fullness cues (turning away, relaxed hands) to avoid overfeeding.',
                'sleep_tips' => 'Total sleep is around 14-16 hours/day, with naps starting to shorten slightly in number.',
                'development_tips' => 'Hands are discovered and explored often — expect lots of hand-watching and mouthing.',
                'safety_tips' => 'Stop using any inclined sleeper or swing for unsupervised sleep — flat surfaces only.',
            ],
            [
                'age_label' => '4 Months',
                'age_min_weeks' => 16, 'age_max_weeks' => 20,
                'weekly_goals' => 'Practice supported sitting for short periods to build core strength.',
                'feeding_tips' => 'Solid foods are typically not started until 6 months unless advised otherwise by your pediatrician.',
                'sleep_tips' => 'The "4-month sleep regression" is common as sleep cycles mature — stay consistent with routines.',
                'development_tips' => 'Rolling (tummy to back) may begin — never leave baby unattended on elevated surfaces.',
                'safety_tips' => 'Lower the crib mattress if baby is starting to push up or rock on hands and knees.',
            ],
            [
                'age_label' => '5 Months',
                'age_min_weeks' => 20, 'age_max_weeks' => 24,
                'weekly_goals' => 'Offer varied textures and objects to grasp to build fine motor skills.',
                'feeding_tips' => 'Watch for readiness signs for solids: good head control, sitting with support, interest in food.',
                'sleep_tips' => 'Keep naps predictable — overtiredness at this age often makes nights harder, not easier.',
                'development_tips' => 'Two-way "conversations" of babbling and cooing back and forth become more common.',
                'safety_tips' => 'Start thinking about outlet covers and cabinet locks ahead of increased mobility.',
            ],
            [
                'age_label' => '6 Months',
                'age_min_weeks' => 24, 'age_max_weeks' => 28,
                'weekly_goals' => 'If cleared by your pediatrician, begin introducing single-ingredient solid foods.',
                'feeding_tips' => 'Start with iron-rich purees or soft finger foods; introduce one new food at a time to watch for reactions.',
                'sleep_tips' => 'Most babies still need 2-3 naps a day; total sleep remains around 12-15 hours.',
                'development_tips' => 'Sitting without support often emerges around now, opening up new ways to play.',
                'safety_tips' => 'Always supervise mealtime closely and avoid choking hazards (whole grapes, nuts, hard raw veg).',
            ],
            [
                'age_label' => '7 Months',
                'age_min_weeks' => 28, 'age_max_weeks' => 30,
                'weekly_goals' => 'Encourage reaching across the body and transferring objects hand to hand.',
                'feeding_tips' => 'Expand food variety across fruits, vegetables, and soft proteins as tolerated.',
                'sleep_tips' => 'Consistent bedtime routines (bath, book, lullaby) help reinforce healthy sleep habits.',
                'development_tips' => 'Stranger and separation awareness can appear — clinginess with new people is normal.',
                'safety_tips' => 'Anchor furniture and TVs as baby may soon start pulling up on nearby objects.',
            ],
            [
                'age_label' => '8 Months',
                'age_min_weeks' => 30, 'age_max_weeks' => 35,
                'weekly_goals' => 'Give supervised floor time to practice crawling or scooting.',
                'feeding_tips' => 'Introduce a cup for water with meals to build early self-feeding skills.',
                'sleep_tips' => 'An 8-9 month regression can occur alongside new mobility skills; keep routines steady.',
                'development_tips' => 'Object permanence develops — peekaboo becomes genuinely exciting.',
                'safety_tips' => 'Gate off stairs and secure any hazardous rooms now that crawling may be underway.',
            ],
            [
                'age_label' => '9 Months',
                'age_min_weeks' => 35, 'age_max_weeks' => 39,
                'weekly_goals' => 'Practice pulling to stand near sturdy, stable furniture.',
                'feeding_tips' => 'Offer soft finger foods to encourage self-feeding and pincer grasp practice.',
                'sleep_tips' => 'Most babies are down to 2 naps a day; watch total daytime sleep to protect nighttime rest.',
                'development_tips' => 'Responds to their name and understands simple words like "no" or "bye-bye".',
                'safety_tips' => 'Check that blind cords, cleaning supplies, and medications are completely inaccessible.',
            ],
            [
                'age_label' => '10 Months',
                'age_min_weeks' => 39, 'age_max_weeks' => 43,
                'weekly_goals' => 'Encourage cruising along furniture with hands held or nearby support.',
                'feeding_tips' => 'Move toward 3 meals plus snacks alongside milk feeds as solids intake grows.',
                'sleep_tips' => 'Keep a firm wake-up time in the morning to anchor the rest of the day\'s schedule.',
                'development_tips' => 'Pincer grasp sharpens, allowing baby to pick up small pieces of food independently.',
                'safety_tips' => 'Re-check the home at floor and crawling height for newly reachable hazards.',
            ],
            [
                'age_label' => '11 Months',
                'age_min_weeks' => 43, 'age_max_weeks' => 48,
                'weekly_goals' => 'Practice standing independently for a few seconds at a time.',
                'feeding_tips' => 'Offer a variety of textures to prepare for more chewing as first steps approach.',
                'sleep_tips' => 'Nap transitions can be bumpy — watch for signs of readiness before dropping a nap.',
                'development_tips' => 'Imitation play increases — baby may copy clapping, waving, or simple gestures.',
                'safety_tips' => 'Keep walking areas clear of trip hazards as baby practices balance.',
            ],
            [
                'age_label' => '12 Months',
                'age_min_weeks' => 48, 'age_max_weeks' => 52,
                'weekly_goals' => 'Celebrate the first birthday! Continue encouraging steps with support.',
                'feeding_tips' => 'Transition from formula/breastmilk-as-primary toward whole milk and table foods, per your pediatrician.',
                'sleep_tips' => 'Most babies are on one long nap by now, with 11-14 hours of total sleep.',
                'development_tips' => 'First words often appear around now, though the range of normal is wide.',
                'safety_tips' => 'Reassess baby-proofing as walking begins — cabinet locks, gates, and corner guards matter more than ever.',
            ],
            [
                'age_label' => '13-14 Months',
                'age_min_weeks' => 52, 'age_max_weeks' => 60,
                'weekly_goals' => 'Encourage independent walking practice on soft, safe surfaces.',
                'feeding_tips' => 'Offer a wide variety of table foods cut into safe, manageable pieces.',
                'sleep_tips' => 'Keep naps predictable; overtiredness can still disrupt nighttime sleep at this stage.',
                'development_tips' => 'Vocabulary starts growing beyond the first word — respond enthusiastically to new sounds.',
                'safety_tips' => 'Secure heavy furniture and appliances that could tip if climbed on.',
            ],
            [
                'age_label' => '15-16 Months',
                'age_min_weeks' => 60, 'age_max_weeks' => 68,
                'weekly_goals' => 'Practice simple instructions like "give me the toy" to build receptive language.',
                'feeding_tips' => 'Toddlers often become more selective eaters — keep offering variety without pressure.',
                'sleep_tips' => 'One nap a day is typical; total sleep is usually 11-14 hours including the nap.',
                'development_tips' => 'Pointing to communicate wants, and to show interest in things, becomes common.',
                'safety_tips' => 'Water safety matters more as curiosity grows — never leave baby unattended near water.',
            ],
            [
                'age_label' => '17-18 Months',
                'age_min_weeks' => 68, 'age_max_weeks' => 78,
                'weekly_goals' => 'Offer simple choices ("apple or banana?") to build early decision-making and language.',
                'feeding_tips' => 'Involve toddlers in simple mealtime routines like using a spoon or cup independently.',
                'sleep_tips' => 'An 18-month sleep regression or nap resistance is common — stay consistent through it.',
                'development_tips' => 'Imitation of household activities (sweeping, "talking" on a phone) becomes a favorite game.',
                'safety_tips' => 'Keep a close eye during outdoor play — running and climbing skills often outpace judgment.',
            ],
            [
                'age_label' => '19-20 Months',
                'age_min_weeks' => 78, 'age_max_weeks' => 86,
                'weekly_goals' => 'Read together daily — naming pictures builds vocabulary quickly at this stage.',
                'feeding_tips' => 'Continue offering balanced meals; appetite naturally varies day to day.',
                'sleep_tips' => 'Keep bedtime consistent even as toddlers start testing boundaries around sleep.',
                'development_tips' => 'Two-word phrases may start to appear ("more milk", "go outside").',
                'safety_tips' => 'Reassess furniture climbing risks as toddlers get bolder and more coordinated.',
            ],
            [
                'age_label' => '21-22 Months',
                'age_min_weeks' => 86, 'age_max_weeks' => 94,
                'weekly_goals' => 'Practice simple sorting and stacking play to build early problem-solving skills.',
                'feeding_tips' => 'Involve toddlers in easy food prep steps (stirring, rinsing) to build interest in meals.',
                'sleep_tips' => 'Consistent wind-down routines still matter even as independence grows.',
                'development_tips' => 'Parallel play with other children becomes more common, even without much interaction yet.',
                'safety_tips' => 'Recheck stair gates and window locks as climbing ability improves.',
            ],
            [
                'age_label' => '23-24 Months',
                'age_min_weeks' => 94, 'age_max_weeks' => 104,
                'weekly_goals' => 'Encourage simple pretend play (feeding a doll, "driving" a toy car).',
                'feeding_tips' => 'Offer regular meal and snack times; independence with utensils continues to grow.',
                'sleep_tips' => 'Most toddlers still need one nap; total sleep is typically 11-14 hours.',
                'development_tips' => 'Vocabulary often grows quickly, and short sentences may start to form.',
                'safety_tips' => 'Continue reinforcing safety basics (holding hands near roads, not touching hot surfaces) through routine and repetition.',
            ],
        ];

        foreach ($guides as $guide) {
            AgeGuide::create($guide);
        }
    }
}
