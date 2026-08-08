<?php

namespace Database\Seeders;

use App\Models\MilestoneDefinition;
use Illuminate\Database\Seeder;

class MilestoneDefinitionSeeder extends Seeder
{
    /**
     * A comprehensive set of general developmental milestones, organized
     * around the typical pediatric checkpoints (2, 4, 6, 9, 12, 15, 18, and
     * 24 months, plus the newborn period) and loosely following well-known
     * CDC/WHO-style age bands across all four tracked categories: motor,
     * cognitive, language, and social. Informational only — every baby
     * develops at their own pace, and a wide range of timing is normal.
     */
    public function run(): void
    {
        if (MilestoneDefinition::query()->exists()) {
            return;
        }

        $milestones = [
            // ---------------------------------------------------------------
            // Newborn (0-4 weeks) — window 0-8 weeks
            // ---------------------------------------------------------------
            ['age_min_weeks' => 0, 'age_max_weeks' => 8, 'category' => 'motor', 'title' => 'Moves arms and legs equally on both sides', 'description' => 'Shows symmetric, jerky movements of all four limbs while awake.'],
            ['age_min_weeks' => 0, 'age_max_weeks' => 8, 'category' => 'motor', 'title' => 'Brings hands near face and mouth', 'description' => 'Reflexively moves hands toward the face, often sucking on fingers or a fist.'],
            ['age_min_weeks' => 0, 'age_max_weeks' => 8, 'category' => 'motor', 'title' => 'Turns head side to side while lying down', 'description' => 'Can turn the head from side to side when placed on their back.'],
            ['age_min_weeks' => 0, 'age_max_weeks' => 8, 'category' => 'cognitive', 'title' => 'Focuses on faces held close', 'description' => 'Can focus on a face or object about 8-12 inches away, roughly the distance to a feeding parent\'s face.'],
            ['age_min_weeks' => 0, 'age_max_weeks' => 8, 'category' => 'cognitive', 'title' => 'Startles at loud or sudden sounds', 'description' => 'Shows a startle (Moro) reflex in response to loud noises or sudden movement.'],
            ['age_min_weeks' => 0, 'age_max_weeks' => 8, 'category' => 'cognitive', 'title' => 'Briefly quiets when picked up or spoken to', 'description' => 'Settles or becomes more alert when comforted or hearing a familiar voice.'],
            ['age_min_weeks' => 0, 'age_max_weeks' => 8, 'category' => 'language', 'title' => 'Cries to communicate different needs', 'description' => 'Uses crying as the primary way to signal hunger, discomfort, or tiredness.'],
            ['age_min_weeks' => 0, 'age_max_weeks' => 8, 'category' => 'language', 'title' => 'Makes small throaty and gurgling sounds', 'description' => 'Produces quiet, involuntary sounds especially during or after feeding.'],
            ['age_min_weeks' => 0, 'age_max_weeks' => 8, 'category' => 'language', 'title' => 'Turns toward familiar voices', 'description' => 'Orients toward the sound of a parent or caregiver speaking nearby.'],
            ['age_min_weeks' => 0, 'age_max_weeks' => 8, 'category' => 'social', 'title' => 'Watches your face intently while feeding', 'description' => 'Maintains eye contact with the feeding parent for short stretches.'],
            ['age_min_weeks' => 0, 'age_max_weeks' => 8, 'category' => 'social', 'title' => 'Calms with skin-to-skin contact', 'description' => 'Settles more easily when held close against a parent\'s skin.'],
            ['age_min_weeks' => 0, 'age_max_weeks' => 8, 'category' => 'social', 'title' => 'Begins to communicate through facial expressions', 'description' => 'Shows early, sometimes reflexive facial expressions like grimacing or reflexive smiling, often during sleep.'],

            // ---------------------------------------------------------------
            // ~2 months — window 4-14 weeks
            // ---------------------------------------------------------------
            ['age_min_weeks' => 4, 'age_max_weeks' => 14, 'category' => 'motor', 'title' => 'Lifts head about 45 degrees during tummy time', 'description' => 'Raises and holds the head up briefly while lying on their stomach.'],
            ['age_min_weeks' => 4, 'age_max_weeks' => 14, 'category' => 'motor', 'title' => 'Moves both arms and legs more smoothly', 'description' => 'Limb movements become less jerky and more fluid than in the newborn period.'],
            ['age_min_weeks' => 4, 'age_max_weeks' => 14, 'category' => 'motor', 'title' => 'Opens and closes hands, brings both to mouth', 'description' => 'Begins purposeful hand movements, including bringing both hands together toward the mouth.'],
            ['age_min_weeks' => 4, 'age_max_weeks' => 14, 'category' => 'cognitive', 'title' => 'Follows a moving object or face past the midline', 'description' => 'Tracks a slowly moving toy or face with their eyes across their field of vision.'],
            ['age_min_weeks' => 4, 'age_max_weeks' => 14, 'category' => 'cognitive', 'title' => 'Begins to act bored if activity doesn\'t change', 'description' => 'May fuss or lose interest when an activity stays the same for too long.'],
            ['age_min_weeks' => 4, 'age_max_weeks' => 14, 'category' => 'cognitive', 'title' => 'Pays close attention to faces', 'description' => 'Studies faces intently, especially those of familiar caregivers.'],
            ['age_min_weeks' => 4, 'age_max_weeks' => 14, 'category' => 'language', 'title' => 'Coos and makes vowel sounds', 'description' => 'Produces soft, drawn-out vowel sounds such as "ahh" or "ooh," especially when content.'],
            ['age_min_weeks' => 4, 'age_max_weeks' => 14, 'category' => 'language', 'title' => 'Reacts to sudden loud sounds', 'description' => 'Startles, cries, or stills in response to unexpected loud noises.'],
            ['age_min_weeks' => 4, 'age_max_weeks' => 14, 'category' => 'language', 'title' => 'Turns head toward interesting sounds', 'description' => 'Orients toward a rattle, voice, or other sound in the room.'],
            ['age_min_weeks' => 4, 'age_max_weeks' => 14, 'category' => 'social', 'title' => 'Shows the first real social smile', 'description' => 'Smiles in response to a face or voice, distinct from earlier reflexive smiling.'],
            ['age_min_weeks' => 4, 'age_max_weeks' => 14, 'category' => 'social', 'title' => 'Briefly self-soothes', 'description' => 'May bring hands to mouth and suck to calm down for short periods.'],
            ['age_min_weeks' => 4, 'age_max_weeks' => 14, 'category' => 'social', 'title' => 'Tries to look at a parent\'s face', 'description' => 'Actively seeks eye contact and visual connection with a caregiver.'],

            // ---------------------------------------------------------------
            // ~4 months — window 12-20 weeks
            // ---------------------------------------------------------------
            ['age_min_weeks' => 12, 'age_max_weeks' => 20, 'category' => 'motor', 'title' => 'Holds head steady without support', 'description' => 'Head control is strong enough to keep the head steady when held upright.'],
            ['age_min_weeks' => 12, 'age_max_weeks' => 20, 'category' => 'motor', 'title' => 'Pushes up onto elbows during tummy time', 'description' => 'Props the upper body up on forearms while on their stomach.'],
            ['age_min_weeks' => 12, 'age_max_weeks' => 20, 'category' => 'motor', 'title' => 'May roll from tummy to back', 'description' => 'Begins the first rolling motion, usually front-to-back before back-to-front.'],
            ['age_min_weeks' => 12, 'age_max_weeks' => 20, 'category' => 'motor', 'title' => 'Holds a toy and shakes it', 'description' => 'Grasps a lightweight toy and moves it, sometimes bringing it to the mouth.'],
            ['age_min_weeks' => 12, 'age_max_weeks' => 20, 'category' => 'motor', 'title' => 'Brings hands to midline purposefully', 'description' => 'Deliberately brings both hands together in front of the body, not just reflexively.'],
            ['age_min_weeks' => 12, 'age_max_weeks' => 20, 'category' => 'cognitive', 'title' => 'Uses hands and eyes together', 'description' => 'Reaches for a toy they can see, coordinating sight and movement.'],
            ['age_min_weeks' => 12, 'age_max_weeks' => 20, 'category' => 'cognitive', 'title' => 'Follows moving objects smoothly with eyes', 'description' => 'Tracks a toy or person moving across the room without losing focus.'],
            ['age_min_weeks' => 12, 'age_max_weeks' => 20, 'category' => 'language', 'title' => 'Babbles with expression', 'description' => 'Makes varied babbling sounds that carry emotional tone, such as excitement or displeasure.'],
            ['age_min_weeks' => 12, 'age_max_weeks' => 20, 'category' => 'language', 'title' => 'Copies sounds heard from others', 'description' => 'Attempts to imitate simple sounds made by a caregiver.'],
            ['age_min_weeks' => 12, 'age_max_weeks' => 20, 'category' => 'language', 'title' => 'Cries in different ways for different needs', 'description' => 'Uses distinguishable cries for hunger, pain, and tiredness.'],
            ['age_min_weeks' => 12, 'age_max_weeks' => 20, 'category' => 'social', 'title' => 'Smiles spontaneously, especially at people', 'description' => 'Smiles without needing to be prompted, particularly when seeing a familiar face.'],
            ['age_min_weeks' => 12, 'age_max_weeks' => 20, 'category' => 'social', 'title' => 'Likes to play and may protest when play stops', 'description' => 'Shows enjoyment during interactive play and may fuss when it ends.'],
            ['age_min_weeks' => 12, 'age_max_weeks' => 20, 'category' => 'social', 'title' => 'Copies facial expressions', 'description' => 'Mimics expressions such as smiling or frowning shown by a caregiver.'],

            // ---------------------------------------------------------------
            // ~6 months — window 20-30 weeks
            // ---------------------------------------------------------------
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'motor', 'title' => 'Rolls in both directions', 'description' => 'Rolls tummy-to-back and back-to-tummy confidently.'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'motor', 'title' => 'Sits with, then briefly without, support', 'description' => 'Sits upright when propped, progressing toward brief independent sitting.'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'motor', 'title' => 'Bears weight on legs when supported', 'description' => 'Pushes down firmly on legs when held in a standing position.'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'motor', 'title' => 'Passes a toy from one hand to the other', 'description' => 'Transfers an object between hands with control.'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'motor', 'title' => 'Rakes small objects with a whole hand', 'description' => 'Uses a raking motion with the whole hand to gather small items.'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'cognitive', 'title' => 'Looks around with curiosity', 'description' => 'Actively scans nearby surroundings and shows interest in new things.'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'cognitive', 'title' => 'Brings things to the mouth to explore them', 'description' => 'Uses mouthing as a primary way to investigate new objects.'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'cognitive', 'title' => 'Reaches for out-of-reach objects', 'description' => 'Stretches or leans to try to get an item that is just out of reach.'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'cognitive', 'title' => 'Begins to understand object permanence', 'description' => 'Starts to grasp that objects still exist even when briefly out of sight.'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'language', 'title' => 'Responds to their own name', 'description' => 'Turns or looks when their name is called from across the room.'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'language', 'title' => 'Makes sounds to express joy and displeasure', 'description' => 'Vocalizes clearly different sounds when happy versus upset.'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'language', 'title' => 'Strings vowel sounds together while babbling', 'description' => 'Chains vowel sounds such as "ah," "eh," and "oh" during vocal play.'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'language', 'title' => 'Begins to make consonant sounds', 'description' => 'Adds early consonant sounds like "m" and "b" into babbling.'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'social', 'title' => 'Knows familiar faces and may notice strangers', 'description' => 'Distinguishes familiar caregivers from unfamiliar people.'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'social', 'title' => 'Likes to play with others, especially parents', 'description' => 'Shows clear enjoyment during interactive games with caregivers.'],
            ['age_min_weeks' => 20, 'age_max_weeks' => 30, 'category' => 'social', 'title' => 'Responds to other people\'s emotions', 'description' => 'Reacts visibly to a caregiver\'s tone of voice or facial expression.'],

            // ---------------------------------------------------------------
            // ~9 months — window 30-42 weeks
            // ---------------------------------------------------------------
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'motor', 'title' => 'Sits without support', 'description' => 'Sits independently for extended periods without needing to be propped.'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'motor', 'title' => 'Crawls or scoots on hands and knees', 'description' => 'Moves across the floor using a crawling, scooting, or army-crawl motion.'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'motor', 'title' => 'Pulls to stand', 'description' => 'Uses furniture or a caregiver to pull themselves up to a standing position.'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'motor', 'title' => 'Moves objects deliberately between hands', 'description' => 'Passes and repositions objects with clear intent and control.'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'motor', 'title' => 'Uses fingers to rake and pick up small food', 'description' => 'Picks up small pieces of soft food using fingers, refining hand-eye coordination.'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'cognitive', 'title' => 'Watches the path of a falling object', 'description' => 'Follows an object with their eyes as it drops or rolls away.'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'cognitive', 'title' => 'Looks for objects hidden while watching', 'description' => 'Searches for a toy that was hidden under a cloth while they watched, showing object permanence.'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'cognitive', 'title' => 'Plays peekaboo', 'description' => 'Anticipates and enjoys the reveal in a simple peekaboo game.'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'cognitive', 'title' => 'Bangs two objects together', 'description' => 'Deliberately bangs two toys or objects together, often repeatedly.'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'language', 'title' => 'Understands "no"', 'description' => 'Shows recognition of the word "no," even if they don\'t always comply.'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'language', 'title' => 'Makes strings of repeated syllables', 'description' => 'Babbles chains like "mamamama" or "bababababa."'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'language', 'title' => 'Copies sounds and gestures of others', 'description' => 'Imitates simple sounds and hand movements made by a caregiver.'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'language', 'title' => 'Points at objects of interest', 'description' => 'Uses a pointed finger to draw attention to something they want or notice.'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'social', 'title' => 'May be wary of strangers', 'description' => 'Shows caution or distress around unfamiliar people.'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'social', 'title' => 'Clings to familiar adults in new situations', 'description' => 'Seeks physical closeness to a trusted caregiver when unsure.'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'social', 'title' => 'Has favorite toys and people', 'description' => 'Shows clear preferences for certain objects or caregivers.'],
            ['age_min_weeks' => 30, 'age_max_weeks' => 42, 'category' => 'social', 'title' => 'Shows several distinct facial expressions', 'description' => 'Displays a wider range of expressions such as surprise, joy, and frustration.'],

            // ---------------------------------------------------------------
            // ~12 months — window 42-56 weeks
            // ---------------------------------------------------------------
            ['age_min_weeks' => 42, 'age_max_weeks' => 56, 'category' => 'motor', 'title' => 'Cruises along furniture', 'description' => 'Walks sideways while holding onto furniture for support.'],
            ['age_min_weeks' => 42, 'age_max_weeks' => 56, 'category' => 'motor', 'title' => 'Takes a few independent steps', 'description' => 'Attempts several steps without support, though may still be unsteady.'],
            ['age_min_weeks' => 42, 'age_max_weeks' => 56, 'category' => 'motor', 'title' => 'Bangs objects together and drops items into a container', 'description' => 'Practices intentional cause-and-effect play with objects and containers.'],
            ['age_min_weeks' => 42, 'age_max_weeks' => 56, 'category' => 'motor', 'title' => 'Uses a mature pincer grasp', 'description' => 'Picks up small objects precisely between thumb and forefinger.'],
            ['age_min_weeks' => 42, 'age_max_weeks' => 56, 'category' => 'cognitive', 'title' => 'Explores objects in different ways', 'description' => 'Shakes, bangs, throws, or drops objects to see what happens.'],
            ['age_min_weeks' => 42, 'age_max_weeks' => 56, 'category' => 'cognitive', 'title' => 'Finds hidden objects easily', 'description' => 'Quickly locates a toy that was hidden under a cup or cloth.'],
            ['age_min_weeks' => 42, 'age_max_weeks' => 56, 'category' => 'cognitive', 'title' => 'Looks at the correct picture or object when named', 'description' => 'Identifies a familiar item or picture when it is named aloud.'],
            ['age_min_weeks' => 42, 'age_max_weeks' => 56, 'category' => 'cognitive', 'title' => 'Copies gestures', 'description' => 'Imitates simple actions like clapping or covering their eyes.'],
            ['age_min_weeks' => 42, 'age_max_weeks' => 56, 'category' => 'language', 'title' => 'Says "mama" and "dada" with meaning', 'description' => 'Uses these words specifically to refer to their parents, not just as sounds.'],
            ['age_min_weeks' => 42, 'age_max_weeks' => 56, 'category' => 'language', 'title' => 'Tries to repeat words they hear', 'description' => 'Attempts to imitate simple words spoken by a caregiver.'],
            ['age_min_weeks' => 42, 'age_max_weeks' => 56, 'category' => 'language', 'title' => 'Responds to simple spoken requests', 'description' => 'Follows a simple instruction such as "come here" without gestures.'],
            ['age_min_weeks' => 42, 'age_max_weeks' => 56, 'category' => 'language', 'title' => 'Uses simple gestures', 'description' => 'Shakes head for "no" or waves for "bye-bye" appropriately.'],
            ['age_min_weeks' => 42, 'age_max_weeks' => 56, 'category' => 'social', 'title' => 'Shows separation anxiety', 'description' => 'May cry or protest when a parent leaves the room.'],
            ['age_min_weeks' => 42, 'age_max_weeks' => 56, 'category' => 'social', 'title' => 'Has favorite people and toys', 'description' => 'Shows a clear, consistent preference for specific caregivers and objects.'],
            ['age_min_weeks' => 42, 'age_max_weeks' => 56, 'category' => 'social', 'title' => 'Shows fear in certain situations', 'description' => 'Displays wariness or fear in response to specific unfamiliar situations.'],
            ['age_min_weeks' => 42, 'age_max_weeks' => 56, 'category' => 'social', 'title' => 'Repeats sounds or actions to get attention', 'description' => 'Deliberately repeats a behavior that previously got a reaction from a caregiver.'],

            // ---------------------------------------------------------------
            // ~15 months — window 56-70 weeks
            // ---------------------------------------------------------------
            ['age_min_weeks' => 56, 'age_max_weeks' => 70, 'category' => 'motor', 'title' => 'Walks alone', 'description' => 'Walks independently without needing to hold on to anything.'],
            ['age_min_weeks' => 56, 'age_max_weeks' => 70, 'category' => 'motor', 'title' => 'Walks up steps and runs holding on', 'description' => 'Climbs stairs with assistance and attempts to run while holding a hand.'],
            ['age_min_weeks' => 56, 'age_max_weeks' => 70, 'category' => 'motor', 'title' => 'Drinks from a cup and eats with fingers', 'description' => 'Manages a cup with some spilling and self-feeds finger foods.'],
            ['age_min_weeks' => 56, 'age_max_weeks' => 70, 'category' => 'motor', 'title' => 'Scribbles on their own with a crayon', 'description' => 'Makes marks on paper without help, holding a crayon in a fist grip.'],
            ['age_min_weeks' => 56, 'age_max_weeks' => 70, 'category' => 'cognitive', 'title' => 'Knows what ordinary things are for', 'description' => 'Understands the purpose of familiar objects like a phone, brush, or spoon.'],
            ['age_min_weeks' => 56, 'age_max_weeks' => 70, 'category' => 'cognitive', 'title' => 'Points to show interest in something', 'description' => 'Points at objects to share attention with a caregiver, not just to request.'],
            ['age_min_weeks' => 56, 'age_max_weeks' => 70, 'category' => 'cognitive', 'title' => 'Puts objects into a container and takes them out', 'description' => 'Enjoys repetitive fill-and-dump play with containers.'],
            ['age_min_weeks' => 56, 'age_max_weeks' => 70, 'category' => 'language', 'title' => 'Says several single words', 'description' => 'Has a small but growing vocabulary of clear, meaningful single words.'],
            ['age_min_weeks' => 56, 'age_max_weeks' => 70, 'category' => 'language', 'title' => 'Says and shakes head for "no"', 'description' => 'Communicates refusal both verbally and with a head shake.'],
            ['age_min_weeks' => 56, 'age_max_weeks' => 70, 'category' => 'language', 'title' => 'Points to ask for something or get help', 'description' => 'Uses pointing purposefully to request an item or assistance.'],
            ['age_min_weeks' => 56, 'age_max_weeks' => 70, 'category' => 'social', 'title' => 'Explores independently with a parent nearby', 'description' => 'Wanders a short distance to explore while checking that a caregiver is close.'],
            ['age_min_weeks' => 56, 'age_max_weeks' => 70, 'category' => 'social', 'title' => 'Shows affection to familiar people', 'description' => 'Offers hugs, kisses, or cuddles to caregivers and other familiar people.'],
            ['age_min_weeks' => 56, 'age_max_weeks' => 70, 'category' => 'social', 'title' => 'Points to show others something interesting', 'description' => 'Draws a caregiver\'s attention to something they find exciting.'],
            ['age_min_weeks' => 56, 'age_max_weeks' => 70, 'category' => 'social', 'title' => 'May have temper tantrums', 'description' => 'Shows frustration through tantrums as independence and limits collide.'],

            // ---------------------------------------------------------------
            // ~18 months — window 68-84 weeks
            // ---------------------------------------------------------------
            ['age_min_weeks' => 68, 'age_max_weeks' => 84, 'category' => 'motor', 'title' => 'Walks steadily and starts to run', 'description' => 'Walks confidently without support and begins attempting to run.'],
            ['age_min_weeks' => 68, 'age_max_weeks' => 84, 'category' => 'motor', 'title' => 'Climbs onto and off furniture without help', 'description' => 'Gets onto and down from a low chair or couch independently.'],
            ['age_min_weeks' => 68, 'age_max_weeks' => 84, 'category' => 'motor', 'title' => 'Kicks a ball', 'description' => 'Can kick a ball forward, even if aim and balance are still developing.'],
            ['age_min_weeks' => 68, 'age_max_weeks' => 84, 'category' => 'motor', 'title' => 'Helps with dressing and feeds self with a spoon', 'description' => 'Cooperates with getting dressed and uses a spoon with some spilling.'],
            ['age_min_weeks' => 68, 'age_max_weeks' => 84, 'category' => 'cognitive', 'title' => 'Points to one body part when asked', 'description' => 'Identifies at least one body part, such as their nose or tummy, on request.'],
            ['age_min_weeks' => 68, 'age_max_weeks' => 84, 'category' => 'cognitive', 'title' => 'Scribbles on their own', 'description' => 'Draws or scribbles spontaneously without being prompted.'],
            ['age_min_weeks' => 68, 'age_max_weeks' => 84, 'category' => 'cognitive', 'title' => 'Follows one-step directions without gestures', 'description' => 'Understands and follows a simple instruction given by words alone.'],
            ['age_min_weeks' => 68, 'age_max_weeks' => 84, 'category' => 'language', 'title' => 'Says a number of single words', 'description' => 'Has a vocabulary of roughly ten or more recognizable words.'],
            ['age_min_weeks' => 68, 'age_max_weeks' => 84, 'category' => 'language', 'title' => 'Points to a named picture or object', 'description' => 'Correctly points to a familiar item or picture when it is named.'],
            ['age_min_weeks' => 68, 'age_max_weeks' => 84, 'category' => 'language', 'title' => 'Follows a simple one-step direction', 'description' => 'Carries out a request such as "give me the ball."'],
            ['age_min_weeks' => 68, 'age_max_weeks' => 84, 'category' => 'social', 'title' => 'Plays simple pretend play', 'description' => 'Acts out simple scenarios, such as feeding a doll or talking on a toy phone.'],
            ['age_min_weeks' => 68, 'age_max_weeks' => 84, 'category' => 'social', 'title' => 'Explores alone but checks in with a parent', 'description' => 'Wanders further while still periodically returning to or glancing at a caregiver.'],
            ['age_min_weeks' => 68, 'age_max_weeks' => 84, 'category' => 'social', 'title' => 'Shows increasing independence', 'description' => 'Insists on doing more tasks without help, even if not fully capable yet.'],

            // ---------------------------------------------------------------
            // ~24 months — window 84-108 weeks
            // ---------------------------------------------------------------
            ['age_min_weeks' => 84, 'age_max_weeks' => 108, 'category' => 'motor', 'title' => 'Runs and climbs well', 'description' => 'Runs steadily and climbs on and off furniture and play equipment with confidence.'],
            ['age_min_weeks' => 84, 'age_max_weeks' => 108, 'category' => 'motor', 'title' => 'Kicks a ball and begins to jump', 'description' => 'Kicks with better control and attempts small two-footed jumps.'],
            ['age_min_weeks' => 84, 'age_max_weeks' => 108, 'category' => 'motor', 'title' => 'Walks up and down stairs holding on', 'description' => 'Navigates stairs one step at a time while holding a railing or hand.'],
            ['age_min_weeks' => 84, 'age_max_weeks' => 108, 'category' => 'motor', 'title' => 'Builds a small tower of blocks', 'description' => 'Stacks four or more blocks before it topples.'],
            ['age_min_weeks' => 84, 'age_max_weeks' => 108, 'category' => 'cognitive', 'title' => 'Begins to sort shapes and colors', 'description' => 'Starts to match and sort simple shapes or colors during play.'],
            ['age_min_weeks' => 84, 'age_max_weeks' => 108, 'category' => 'cognitive', 'title' => 'Completes sentences and rhymes in familiar books', 'description' => 'Fills in the missing word in a well-known story or nursery rhyme.'],
            ['age_min_weeks' => 84, 'age_max_weeks' => 108, 'category' => 'cognitive', 'title' => 'Plays simple pretend or make-believe games', 'description' => 'Engages in imaginative play with toys, dolls, or stuffed animals.'],
            ['age_min_weeks' => 84, 'age_max_weeks' => 108, 'category' => 'cognitive', 'title' => 'Follows two-step instructions', 'description' => 'Carries out a two-part request such as "pick up the toy and put it in the box."'],
            ['age_min_weeks' => 84, 'age_max_weeks' => 108, 'category' => 'language', 'title' => 'Combines two or more words', 'description' => 'Forms simple phrases like "more milk" or "go outside."'],
            ['age_min_weeks' => 84, 'age_max_weeks' => 108, 'category' => 'language', 'title' => 'Points to things in a book when named', 'description' => 'Identifies familiar objects or animals in a picture book on request.'],
            ['age_min_weeks' => 84, 'age_max_weeks' => 108, 'category' => 'language', 'title' => 'Names a few familiar people and body parts', 'description' => 'Says the names of close family members and points out several body parts.'],
            ['age_min_weeks' => 84, 'age_max_weeks' => 108, 'category' => 'language', 'title' => 'Has a vocabulary of 50 or more words', 'description' => 'Uses a wide and rapidly growing range of recognizable words.'],
            ['age_min_weeks' => 84, 'age_max_weeks' => 108, 'category' => 'social', 'title' => 'Shows more independence, including defiance', 'description' => 'Asserts preferences strongly and may resist help or instructions.'],
            ['age_min_weeks' => 84, 'age_max_weeks' => 108, 'category' => 'social', 'title' => 'Plays alongside other children', 'description' => 'Engages in parallel play near peers, with growing interest in including them.'],
            ['age_min_weeks' => 84, 'age_max_weeks' => 108, 'category' => 'social', 'title' => 'Shows excitement and a growing sense of self', 'description' => 'Displays enthusiasm and an increasing awareness of being separate from caregivers.'],
            ['age_min_weeks' => 84, 'age_max_weeks' => 108, 'category' => 'social', 'title' => 'Copies others, especially adults and older children', 'description' => 'Imitates everyday actions and behaviors observed in people around them.'],
        ];

        foreach ($milestones as $milestone) {
            MilestoneDefinition::create($milestone);
        }
    }
}
