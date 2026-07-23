<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Topic;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $topics = Topic::all()->keyBy('slug');
        if ($topics->isEmpty()) {
            $this->command?->warn('Topics not found. Run TopicSeeder first.');
            return;
        }

        $questions = [
            // Heart of Algebra
            [
                'topic_slug' => 'heart-of-algebra',
                'type' => 'mcq',
                'difficulty' => 'easy',
                'is_diagnostic' => true,
                'prompt' => 'If $2x - 5 = 11$, what is the value of $x$?',
                'options' => json_encode(['A' => '3', 'B' => '6', 'C' => '8', 'D' => '16']),
                'correct_answer' => 'C',
                'explanation' => 'Add 5 to both sides to get $2x = 16$. Then divide by 2 to get $x = 8$.'
            ],
            [
                'topic_slug' => 'heart-of-algebra',
                'type' => 'mcq',
                'difficulty' => 'medium',
                'is_diagnostic' => true,
                'prompt' => 'Which of the following is equivalent to $3(x + 5) - 6$?',
                'options' => json_encode(['A' => '$3x - 1$', 'B' => '$3x - 3$', 'C' => '$3x + 9$', 'D' => '$15x - 6$']),
                'correct_answer' => 'C',
                'explanation' => 'Distribute the 3 to get $3x + 15$. Subtract 6 to get $3x + 9$.'
            ],
            [
                'topic_slug' => 'heart-of-algebra',
                'type' => 'free_response',
                'difficulty' => 'medium',
                'is_diagnostic' => true,
                'prompt' => 'If $y = 3x - 4$ and $y = 5$, what is $x$?',
                'options' => null,
                'correct_answer' => '3',
                'explanation' => 'Substitute $y = 5$ to get $5 = 3x - 4$. Add 4 to both sides: $9 = 3x$. Divide by 3: $x = 3$.'
            ],

            // Advanced Math
            [
                'topic_slug' => 'advanced-math',
                'type' => 'mcq',
                'difficulty' => 'medium',
                'is_diagnostic' => true,
                'prompt' => 'If $f(x) = x^2 - 4x + 4$, what is $f(2)$?',
                'options' => json_encode(['A' => '-4', 'B' => '0', 'C' => '4', 'D' => '8']),
                'correct_answer' => 'B',
                'explanation' => 'Substitute $x = 2$: $f(2) = (2)^2 - 4(2) + 4 = 4 - 8 + 4 = 0$.'
            ],
            [
                'topic_slug' => 'advanced-math',
                'type' => 'mcq',
                'difficulty' => 'hard',
                'is_diagnostic' => true,
                'prompt' => 'Which expression is equivalent to $(x^2 + 3x + 2) / (x + 1)$ for $x \\neq -1$?',
                'options' => json_encode(['A' => '$x + 2$', 'B' => '$x + 1$', 'C' => '$x + 3$', 'D' => '$x$']),
                'correct_answer' => 'A',
                'explanation' => 'Factor the numerator: $(x+1)(x+2)$. The $(x+1)$ terms cancel, leaving $x+2$.'
            ],
            
            // Problem Solving & Data Analysis
            [
                'topic_slug' => 'problem-solving',
                'type' => 'mcq',
                'difficulty' => 'easy',
                'is_diagnostic' => true,
                'prompt' => 'A store sells a shirt for $20. If the price is discounted by 25%, what is the new price?',
                'options' => json_encode(['A' => '$5', 'B' => '$10', 'C' => '$15', 'D' => '$25']),
                'correct_answer' => 'C',
                'explanation' => 'A 25% discount on $20 is $5. So $20 - $5 = $15.'
            ],
            [
                'topic_slug' => 'problem-solving',
                'type' => 'free_response',
                'difficulty' => 'medium',
                'is_diagnostic' => true,
                'prompt' => 'If the ratio of boys to girls in a class is 3:5 and there are 40 students in total, how many boys are there?',
                'options' => null,
                'correct_answer' => '15',
                'explanation' => 'Total ratio parts = 3 + 5 = 8. Each part = 40 / 8 = 5. Boys = 3 * 5 = 15.'
            ],

            // Geometry
            [
                'topic_slug' => 'geometry',
                'type' => 'mcq',
                'difficulty' => 'easy',
                'is_diagnostic' => true,
                'prompt' => 'What is the area of a rectangle with length 8 and width 5?',
                'options' => json_encode(['A' => '13', 'B' => '26', 'C' => '40', 'D' => '80']),
                'correct_answer' => 'C',
                'explanation' => 'Area = length × width = $8 \\times 5 = 40$.'
            ],
            [
                'topic_slug' => 'geometry',
                'type' => 'mcq',
                'difficulty' => 'medium',
                'is_diagnostic' => true,
                'prompt' => 'In a right triangle, one angle is 90° and another is 30°. What is the third angle?',
                'options' => json_encode(['A' => '30°', 'B' => '45°', 'C' => '60°', 'D' => '90°']),
                'correct_answer' => 'C',
                'explanation' => 'The sum of angles in a triangle is 180°. 180 - 90 - 30 = 60°.'
            ],
            [
                'topic_slug' => 'geometry',
                'type' => 'mcq',
                'difficulty' => 'hard',
                'is_diagnostic' => true,
                'prompt' => 'The equation of a circle is $x^2 + y^2 = 16$. What is its radius?',
                'options' => json_encode(['A' => '4', 'B' => '8', 'C' => '16', 'D' => '256']),
                'correct_answer' => 'A',
                'explanation' => 'The standard equation is $x^2 + y^2 = r^2$. Since $r^2 = 16$, $r = 4$.',
                'desmos_expressions' => json_encode(['x^2+y^2=16'])
            ],

            // Trigonometry
            [
                'topic_slug' => 'trigonometry',
                'type' => 'mcq',
                'difficulty' => 'medium',
                'is_diagnostic' => true,
                'prompt' => 'If $\\sin(\\theta) = 3/5$ and $\\theta$ is in the first quadrant, what is $\\cos(\\theta)$?',
                'options' => json_encode(['A' => '3/5', 'B' => '4/5', 'C' => '5/4', 'D' => '4/3']),
                'correct_answer' => 'B',
                'explanation' => 'Using $\\sin^2 + \\cos^2 = 1$, $(3/5)^2 + \\cos^2 = 1 \\implies \\cos = 4/5$.'
            ],
            [
                'topic_slug' => 'trigonometry',
                'type' => 'mcq',
                'difficulty' => 'hard',
                'is_diagnostic' => true,
                'prompt' => 'Which of the following is equivalent to $\\tan(x) \\cos(x)$?',
                'options' => json_encode(['A' => '$\\sin(x)$', 'B' => '$\\cos(x)$', 'C' => '$\\sec(x)$', 'D' => '$\\csc(x)$']),
                'correct_answer' => 'A',
                'explanation' => '$\\tan(x) = \\sin(x)/\\cos(x)$. Multiplying by $\\cos(x)$ gives $\\sin(x)$.'
            ],

            // Functions
            [
                'topic_slug' => 'functions',
                'type' => 'mcq',
                'difficulty' => 'easy',
                'is_diagnostic' => true,
                'prompt' => 'If $g(x) = 2x - 3$, for what value of $x$ is $g(x) = 7$?',
                'options' => json_encode(['A' => '2', 'B' => '3', 'C' => '5', 'D' => '7']),
                'correct_answer' => 'C',
                'explanation' => '$2x - 3 = 7 \\implies 2x = 10 \\implies x = 5$.'
            ],
            [
                'topic_slug' => 'functions',
                'type' => 'free_response',
                'difficulty' => 'medium',
                'is_diagnostic' => true,
                'prompt' => 'If $h(x) = x^2$ and $k(x) = x + 2$, what is $h(k(3))$?',
                'options' => null,
                'correct_answer' => '25',
                'explanation' => '$k(3) = 3 + 2 = 5$. Then $h(5) = 5^2 = 25$.'
            ],
            [
                'topic_slug' => 'functions',
                'type' => 'mcq',
                'difficulty' => 'hard',
                'is_diagnostic' => true,
                'prompt' => 'What is the y-intercept of the graph of $y = 3(2)^x$?',
                'options' => json_encode(['A' => '(0, 0)', 'B' => '(0, 2)', 'C' => '(0, 3)', 'D' => '(0, 6)']),
                'correct_answer' => 'C',
                'explanation' => 'Substitute $x=0$: $y = 3(2)^0 = 3(1) = 3$.',
                'desmos_expressions' => json_encode(['y=3(2)^x'])
            ],

            // Statistics
            [
                'topic_slug' => 'statistics',
                'type' => 'mcq',
                'difficulty' => 'easy',
                'is_diagnostic' => true,
                'prompt' => 'What is the mean of the numbers 2, 4, 6, 8, and 10?',
                'options' => json_encode(['A' => '5', 'B' => '6', 'C' => '7', 'D' => '8']),
                'correct_answer' => 'B',
                'explanation' => 'Sum is 30. There are 5 numbers. 30 / 5 = 6.'
            ],
            [
                'topic_slug' => 'statistics',
                'type' => 'mcq',
                'difficulty' => 'medium',
                'is_diagnostic' => true,
                'prompt' => 'If the median of a sorted list of 5 numbers is 12, what is true about the numbers?',
                'options' => json_encode(['A' => 'The mean is 12', 'B' => 'The third number is 12', 'C' => 'The largest is 12', 'D' => 'The smallest is 12']),
                'correct_answer' => 'B',
                'explanation' => 'The median of 5 sorted numbers is the middle (3rd) number.'
            ],
            [
                'topic_slug' => 'statistics',
                'type' => 'mcq',
                'difficulty' => 'hard',
                'is_diagnostic' => true,
                'prompt' => 'Which has a larger standard deviation: Set A {4,5,6} or Set B {1,5,9}?',
                'options' => json_encode(['A' => 'Set A', 'B' => 'Set B', 'C' => 'They are equal', 'D' => 'Cannot be determined']),
                'correct_answer' => 'B',
                'explanation' => 'Set B values are spread further from the mean (5).'
            ]
        ];

        foreach ($questions as $q) {
            $topic = $topics->get($q['topic_slug']);
            if ($topic) {
                unset($q['topic_slug']);
                $q['topic_id'] = $topic->id;
                Question::create($q);
            }
        }

        $this->command?->info('✓ Diagnostic questions seeded.');
    }
}
