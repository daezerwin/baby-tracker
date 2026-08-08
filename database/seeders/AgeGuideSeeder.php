<?php

namespace Database\Seeders;

use App\Models\AgeGuide;
use Illuminate\Database\Seeder;

class AgeGuideSeeder extends Seeder
{
    /**
     * Comprehensive, general, non-medical guidance by age band — biweekly
     * for the first 12 weeks, then monthly through 24 months. Always defer
     * to your pediatrician for anything specific to your baby.
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
                'weekly_goals' => 'Focus on feeding, bonding, and recovery. Aim for 8-12 feeds a day and lots of skin-to-skin contact. It is normal for these first two weeks to feel disorienting — there is no schedule to "get right" yet, just feeding, sleeping, and getting to know each other.',
                'feeding_tips' => 'Feed on demand roughly every 2-3 hours, or more often during cluster-feeding periods. Watch for early hunger cues like rooting, lip-smacking, and hand-to-mouth movements rather than waiting for crying, which is a late sign. Expect some weight loss in the first few days followed by a return to birth weight by around two weeks.',
                'sleep_tips' => 'Newborns sleep 14-17 hours a day, but only in short 1-3 hour stretches, day and night — this is normal and not something to "fix" yet. Always place baby on their back on a firm, flat surface with nothing else in the sleep space (no blankets, pillows, bumpers, or toys).',
                'development_tips' => 'Vision is blurry beyond about 8-12 inches, so your face during feeding is often the clearest thing baby can see. Hearing is fully developed at birth — talk, sing, and narrate your day often, even though baby can\'t respond yet. Reflexes like rooting, grasping, and the Moro (startle) reflex are all expected at this stage.',
                'safety_tips' => 'Always support the head and neck when holding or moving baby. Keep the crib or bassinet completely bare, use a firm mattress, and never leave baby unattended on a raised or soft surface such as a bed, couch, or changing table.',
            ],
            [
                'age_label' => 'Weeks 2-4',
                'age_min_weeks' => 2, 'age_max_weeks' => 4,
                'weekly_goals' => 'Establish a loose feeding rhythm and start noticing baby\'s early "alert" periods — the short windows when they are calm, awake, and open to interaction. Try not to compare your routine to anyone else\'s; every baby settles at a different pace.',
                'feeding_tips' => 'Growth spurts around 2-3 weeks can temporarily increase feeding frequency or fussiness — this is normal and usually passes within a few days. If breastfeeding, frequent feeding during a growth spurt helps signal your body to increase supply.',
                'sleep_tips' => 'Begin a very simple wind-down routine before naps and bedtime (dim lights, quieter voice, swaddle if used) to gently start cueing sleep, without expecting a strict schedule yet. Continue safe sleep practices: back sleeping, firm surface, bare crib.',
                'development_tips' => 'Baby may start to briefly follow a face or a high-contrast object with their eyes. Reflexive smiles (often during sleep) are common now, though true social smiles usually come a bit later.',
                'safety_tips' => 'Never leave baby unattended on any raised surface, even for a moment. Double-check the car seat is correctly installed and rear-facing, and that the harness fits snugly.',
            ],
            [
                'age_label' => 'Weeks 4-6',
                'age_min_weeks' => 4, 'age_max_weeks' => 6,
                'weekly_goals' => 'Start short daily tummy-time sessions — even 1-2 minutes, a few times a day — to build neck and shoulder strength. Keep sessions playful and stop if baby gets upset; a little often works better than one long session.',
                'feeding_tips' => 'Feeds may start to space out slightly, often to every 2.5-3.5 hours, though this varies a lot between babies. Continue feeding on demand rather than by the clock at this stage.',
                'sleep_tips' => 'One slightly longer sleep stretch may start to emerge, often earlier in the night rather than at the end. Total sleep is still around 14-17 hours a day, spread across day and night.',
                'development_tips' => 'Watch for the first true social smile — a big emotional milestone for many parents, usually appearing between 4 and 6 weeks. Baby may also start making small cooing or throaty sounds.',
                'safety_tips' => 'Keep small objects, pet hazards, cords, and blind strings well out of reach as baby becomes more alert and starts to notice their surroundings more.',
            ],
            [
                'age_label' => 'Weeks 6-8',
                'age_min_weeks' => 6, 'age_max_weeks' => 8,
                'weekly_goals' => 'Increase tummy time gradually, aiming for a few short sessions spread through the day rather than one long stretch. This is also a good age to start noticing baby\'s individual temperament and preferences.',
                'feeding_tips' => 'If bottle feeding, continue paced feeding (holding the bottle horizontally, taking breaks) to help baby self-regulate how much they take in and reduce overfeeding or gas.',
                'sleep_tips' => 'Daytime naps may start to organize into roughly 3-4 shorter naps rather than the more constant dozing of the newborn weeks. Nighttime sleep is still fragmented for most babies at this age.',
                'development_tips' => 'Cooing sounds often begin or increase around now — respond by talking or "cooing" back to encourage early back-and-forth communication. Baby may also start tracking moving objects more smoothly.',
                'safety_tips' => 'Schedule or attend the 6-8 week wellness check-up and routine vaccinations if due, and use this visit to raise any feeding, sleep, or development questions with your pediatrician.',
            ],
            [
                'age_label' => 'Weeks 8-10',
                'age_min_weeks' => 8, 'age_max_weeks' => 10,
                'weekly_goals' => 'Encourage reaching and batting at toys during awake time by offering lightweight rattles or dangling toys just within reach. Continue building tummy time tolerance.',
                'feeding_tips' => 'Continue on-demand feeding; appetite may increase as baby becomes more active and alert during the day.',
                'sleep_tips' => 'Keep the sleep space consistent — using the same crib or bassinet in the same location helps sleep associations start to form.',
                'development_tips' => 'Head control is improving noticeably; baby may hold their head steady for a few seconds when held upright, and lift it higher during tummy time.',
                'safety_tips' => 'Re-check smoke and carbon monoxide detectors as baby starts spending more time in different rooms of the home during the day.',
            ],
            [
                'age_label' => 'Weeks 10-12',
                'age_min_weeks' => 10, 'age_max_weeks' => 12,
                'weekly_goals' => 'Introduce a simple, repeatable daily rhythm — wake, feed, play, sleep — rather than a strict clock-based schedule. This kind of loose structure tends to be easier for both baby and parents to settle into.',
                'feeding_tips' => 'Feeding sessions may become more efficient and slightly shorter as baby gets stronger and more coordinated at feeding.',
                'sleep_tips' => 'A longer nighttime stretch (often 4-6 hours) starts to appear for some babies around this age, though plenty of babies still wake more frequently, which is also normal.',
                'development_tips' => 'Babbling and more varied vocal sounds continue to develop — keep narrating your day out loud, since baby is absorbing language constantly even before they can respond in kind.',
                'safety_tips' => 'Start baby-proofing surfaces baby may soon roll toward, and avoid leaving them unattended on beds, changing tables, or sofas, since rolling can begin unexpectedly.',
            ],
            [
                'age_label' => '3 Months',
                'age_min_weeks' => 12, 'age_max_weeks' => 16,
                'weekly_goals' => 'Work on tummy time until baby can comfortably push up onto their forearms and lift their head and chest well off the ground.',
                'feeding_tips' => 'Feeding volumes grow steadily; watch for fullness cues like turning away, slowing down, or relaxed hands to avoid overfeeding, especially with a bottle.',
                'sleep_tips' => 'Total sleep is typically around 14-16 hours a day, with daytime naps gradually starting to consolidate into fewer, sometimes slightly longer, stretches.',
                'development_tips' => 'Hands are discovered and explored often at this age — expect lots of hand-watching, finger-wiggling, and bringing hands to the mouth.',
                'safety_tips' => 'Stop using any inclined sleeper, swing, or car seat for unsupervised sleep. Flat, firm surfaces are the only safe place for sleep, even for naps.',
            ],
            [
                'age_label' => '4 Months',
                'age_min_weeks' => 16, 'age_max_weeks' => 20,
                'weekly_goals' => 'Practice supported sitting for short periods to build core and back strength ahead of independent sitting later on.',
                'feeding_tips' => 'Solid foods are typically not started until around 6 months unless your pediatrician has advised otherwise — breastmilk or formula remains the primary source of nutrition for now.',
                'sleep_tips' => 'The well-known "4-month sleep regression" is common as sleep cycles mature and become more like adult sleep patterns. Staying consistent with routines tends to help this phase pass more smoothly.',
                'development_tips' => 'Rolling from tummy to back may begin around now — never leave baby unattended on a changing table, bed, or other elevated surface, even briefly.',
                'safety_tips' => 'Lower the crib mattress to the next setting if baby is starting to push up, rock on hands and knees, or otherwise become more mobile.',
            ],
            [
                'age_label' => '5 Months',
                'age_min_weeks' => 20, 'age_max_weeks' => 24,
                'weekly_goals' => 'Offer varied textures and objects to grasp — soft toys, rattles, textured balls — to build fine motor skills and sensory exploration.',
                'feeding_tips' => 'Watch for readiness signs for starting solids: good head control, sitting with support, and genuine interest in what others are eating.',
                'sleep_tips' => 'Keep naps as predictable as reasonably possible — overtiredness at this age often makes nights harder, not easier, so watching wake windows can help.',
                'development_tips' => 'Two-way "conversations" made up of babbling and cooing back and forth with a caregiver become more common and more intentional.',
                'safety_tips' => 'Start thinking ahead about outlet covers and cabinet locks in anticipation of increased mobility over the next couple of months.',
            ],
            [
                'age_label' => '6 Months',
                'age_min_weeks' => 24, 'age_max_weeks' => 28,
                'weekly_goals' => 'If cleared by your pediatrician, begin introducing single-ingredient solid foods, offered alongside continued breastmilk or formula feeds.',
                'feeding_tips' => 'Start with iron-rich purees or appropriately prepared soft finger foods. Introduce one new food at a time, waiting a few days between new foods to watch for any reactions.',
                'sleep_tips' => 'Most babies still need 2-3 naps a day at this age; total sleep, including naps, generally remains around 12-15 hours.',
                'development_tips' => 'Sitting without support often emerges around now, opening up entirely new ways for baby to play, reach, and explore their surroundings.',
                'safety_tips' => 'Always supervise mealtimes closely and avoid common choking hazards such as whole grapes, whole nuts, popcorn, and hard raw vegetables.',
            ],
            [
                'age_label' => '7 Months',
                'age_min_weeks' => 28, 'age_max_weeks' => 30,
                'weekly_goals' => 'Encourage reaching across the body ("crossing midline") and transferring objects from hand to hand during play.',
                'feeding_tips' => 'Expand food variety across fruits, vegetables, and soft proteins as tolerated, continuing to introduce new foods one at a time.',
                'sleep_tips' => 'Consistent bedtime routines — bath, book, lullaby, in the same order each night — help reinforce healthy sleep associations.',
                'development_tips' => 'Stranger and separation awareness can appear around now; clinginess with new people or in new environments is a normal developmental phase, not a regression.',
                'safety_tips' => 'Anchor tall or heavy furniture and TVs to the wall, as baby may soon start pulling up on nearby objects for support.',
            ],
            [
                'age_label' => '8 Months',
                'age_min_weeks' => 30, 'age_max_weeks' => 35,
                'weekly_goals' => 'Give plenty of supervised floor time to practice crawling, scooting, or other early methods of getting around.',
                'feeding_tips' => 'Introduce an open or straw cup with water at mealtimes to start building early self-feeding and drinking skills.',
                'sleep_tips' => 'An 8-9 month sleep regression can occur alongside new mobility skills and separation anxiety; keeping routines steady through it tends to help.',
                'development_tips' => 'Object permanence continues to develop — peekaboo becomes genuinely exciting because baby now understands hidden things still exist.',
                'safety_tips' => 'Gate off stairs and secure any hazardous rooms (kitchen, bathroom) now that crawling or scooting may be well underway.',
            ],
            [
                'age_label' => '9 Months',
                'age_min_weeks' => 35, 'age_max_weeks' => 39,
                'weekly_goals' => 'Practice pulling to stand near sturdy, stable furniture that won\'t tip, and offer plenty of supervised standing practice.',
                'feeding_tips' => 'Offer soft finger foods to encourage self-feeding and to give baby practice with a pincer grasp.',
                'sleep_tips' => 'Most babies are down to 2 naps a day by now; watch total daytime sleep so it doesn\'t start crowding out nighttime rest.',
                'development_tips' => 'Baby increasingly responds to their own name and starts understanding simple words and phrases like "no" or "bye-bye," even before they can say them.',
                'safety_tips' => 'Check that blind cords, cleaning supplies, medications, and small choking hazards are completely inaccessible at floor and crawling height.',
            ],
            [
                'age_label' => '10 Months',
                'age_min_weeks' => 39, 'age_max_weeks' => 43,
                'weekly_goals' => 'Encourage cruising along furniture, with hands held or nearby support available as baby builds confidence and balance.',
                'feeding_tips' => 'Move toward roughly 3 meals a day plus snacks alongside milk feeds as solid food intake continues to grow.',
                'sleep_tips' => 'Keep a firm, consistent wake-up time in the morning — this helps anchor the rest of the day\'s nap and bedtime schedule.',
                'development_tips' => 'Pincer grasp sharpens noticeably, allowing baby to pick up small pieces of food and objects with increasing precision.',
                'safety_tips' => 'Re-check the home at floor and crawling height for newly reachable hazards, since baby\'s range and curiosity are both expanding.',
            ],
            [
                'age_label' => '11 Months',
                'age_min_weeks' => 43, 'age_max_weeks' => 48,
                'weekly_goals' => 'Practice standing independently for a few seconds at a time, building toward the first unsupported steps.',
                'feeding_tips' => 'Offer a wider variety of textures to prepare baby for more chewing as they approach the toddler diet and first steps.',
                'sleep_tips' => 'Nap transitions can be bumpy around this age — watch for real signs of readiness (fighting a nap consistently) before dropping one.',
                'development_tips' => 'Imitation play increases noticeably; baby may copy clapping, waving, or other simple gestures they see modeled.',
                'safety_tips' => 'Keep walking and cruising areas clear of trip hazards, loose rugs, and cords as baby practices balance and early mobility.',
            ],
            [
                'age_label' => '12 Months',
                'age_min_weeks' => 48, 'age_max_weeks' => 52,
                'weekly_goals' => 'Celebrate the first birthday! Continue encouraging steps with support while following baby\'s own pace toward independent walking.',
                'feeding_tips' => 'Per your pediatrician\'s guidance, this is typically when many families begin transitioning from formula or breastmilk as the primary drink toward whole milk and more table foods.',
                'sleep_tips' => 'Most babies are on one long nap by now, with total sleep — including that nap — generally around 11-14 hours.',
                'development_tips' => 'First words often appear around this age, though the normal range for first words is wide and shouldn\'t be a source of worry on its own.',
                'safety_tips' => 'Reassess baby-proofing as walking begins in earnest — cabinet locks, stair gates, and corner guards matter more than ever once baby is upright and mobile.',
            ],
            [
                'age_label' => '13-14 Months',
                'age_min_weeks' => 52, 'age_max_weeks' => 60,
                'weekly_goals' => 'Encourage independent walking practice on soft, safe surfaces like carpet or grass, where falls are lower-stakes.',
                'feeding_tips' => 'Offer a wide variety of table foods cut into safe, manageable pieces to build chewing skills and food acceptance.',
                'sleep_tips' => 'Keep naps predictable; overtiredness can still disrupt nighttime sleep considerably at this stage.',
                'development_tips' => 'Vocabulary starts growing beyond the first word — respond enthusiastically to new sounds and attempts at words to encourage more.',
                'safety_tips' => 'Secure heavy furniture and appliances that could tip over if climbed on, and reassess this as climbing skills improve.',
            ],
            [
                'age_label' => '15-16 Months',
                'age_min_weeks' => 60, 'age_max_weeks' => 68,
                'weekly_goals' => 'Practice simple instructions like "give me the toy" to build receptive language ahead of more complex requests later.',
                'feeding_tips' => 'Toddlers often become more selective eaters around this age — keep offering variety without applying pressure to finish or try foods.',
                'sleep_tips' => 'One nap a day is typical by now; total sleep, including the nap, is usually around 11-14 hours.',
                'development_tips' => 'Pointing to communicate wants, and to show interest in things they find exciting, becomes a common and important form of communication.',
                'safety_tips' => 'Water safety matters more as curiosity grows — never leave a toddler unattended near a bath, pool, or other open water, even for a moment.',
            ],
            [
                'age_label' => '17-18 Months',
                'age_min_weeks' => 68, 'age_max_weeks' => 78,
                'weekly_goals' => 'Offer simple choices ("apple or banana?") to build early decision-making skills and encourage more language use.',
                'feeding_tips' => 'Involve toddlers in simple mealtime routines, like using a spoon or drinking from a cup independently, even if it\'s messy.',
                'sleep_tips' => 'An 18-month sleep regression or nap resistance is fairly common — staying consistent with routines through it usually helps it pass.',
                'development_tips' => 'Imitation of household activities, like pretend sweeping or "talking" on a toy phone, becomes a favorite and very telling form of play.',
                'safety_tips' => 'Keep a close eye during outdoor play, since running and climbing skills at this age often outpace judgment about what\'s safe.',
            ],
            [
                'age_label' => '19-20 Months',
                'age_min_weeks' => 78, 'age_max_weeks' => 86,
                'weekly_goals' => 'Read together daily — naming pictures and objects in books is one of the fastest ways to build vocabulary at this stage.',
                'feeding_tips' => 'Continue offering balanced meals; appetite naturally varies quite a bit day to day at this age, which is normal.',
                'sleep_tips' => 'Keep bedtime consistent even as toddlers start testing boundaries — routine tends to matter more, not less, during this phase.',
                'development_tips' => 'Two-word phrases may start to appear, such as "more milk" or "go outside," as vocabulary and grammar both develop.',
                'safety_tips' => 'Reassess furniture-climbing risks as toddlers get bolder and more physically coordinated.',
            ],
            [
                'age_label' => '21-22 Months',
                'age_min_weeks' => 86, 'age_max_weeks' => 94,
                'weekly_goals' => 'Practice simple sorting and stacking play — shape sorters, blocks, stacking cups — to build early problem-solving skills.',
                'feeding_tips' => 'Involve toddlers in easy food-prep steps, like stirring or rinsing produce, to build interest and investment in mealtimes.',
                'sleep_tips' => 'Consistent wind-down routines still matter a great deal even as independence and opinions grow.',
                'development_tips' => 'Parallel play alongside other children becomes more common, even without much direct interaction yet — this is a normal stage on the way to cooperative play.',
                'safety_tips' => 'Recheck stair gates and window locks periodically as climbing ability continues to improve.',
            ],
            [
                'age_label' => '23-24 Months',
                'age_min_weeks' => 94, 'age_max_weeks' => 104,
                'weekly_goals' => 'Encourage simple pretend play — feeding a doll, "driving" a toy car — which supports both language and social development.',
                'feeding_tips' => 'Offer regular meal and snack times; independence with utensils continues to grow, along with strong food preferences.',
                'sleep_tips' => 'Most toddlers still need one nap a day; total sleep is typically around 11-14 hours including that nap.',
                'development_tips' => 'Vocabulary often grows quickly around now, and short two-to-three-word sentences may start to form regularly.',
                'safety_tips' => 'Continue reinforcing safety basics — holding hands near roads, not touching hot surfaces — through calm, consistent routine and repetition rather than one-off warnings.',
            ],
        ];

        foreach ($guides as $guide) {
            AgeGuide::create($guide);
        }
    }
}
