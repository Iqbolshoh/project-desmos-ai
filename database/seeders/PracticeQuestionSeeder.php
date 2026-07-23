<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Topic;

class PracticeQuestionSeeder extends Seeder
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
            ['topic_slug' => 'heart-of-algebra', 'type' => 'mcq', 'difficulty' => 'easy',
                'prompt' => 'If $3x + 7 = 22$, what is the value of $x$?',
                'options' => ['A' => '3', 'B' => '5', 'C' => '7', 'D' => '15'], 'correct_answer' => 'B',
                'explanation' => 'Subtract 7 from both sides: $3x = 15$. Divide by 3: $x = 5$.'],
            ['topic_slug' => 'heart-of-algebra', 'type' => 'free_response', 'difficulty' => 'easy',
                'prompt' => 'If $y - 8 = 2y - 20$, what is the value of $y$?',
                'options' => null, 'correct_answer' => '12',
                'explanation' => 'Add 20 to both sides and subtract $y$: $-8 + 20 = 2y - y$, so $y = 12$.'],
            ['topic_slug' => 'heart-of-algebra', 'type' => 'mcq', 'difficulty' => 'medium',
                'prompt' => 'If $2x - 3y = 12$ and $y = 2$, what is the value of $x$?',
                'options' => ['A' => '3', 'B' => '6', 'C' => '9', 'D' => '18'], 'correct_answer' => 'C',
                'explanation' => 'Substitute $y = 2$: $2x - 6 = 12 \\implies 2x = 18 \\implies x = 9$.'],
            ['topic_slug' => 'heart-of-algebra', 'type' => 'free_response', 'difficulty' => 'medium',
                'prompt' => 'The sum of two numbers is 24 and their difference is 8. What is the larger number?',
                'options' => null, 'correct_answer' => '16',
                'explanation' => 'Adding the equations $x+y=24$ and $x-y=8$ gives $2x = 32$, so $x = 16$.'],
            ['topic_slug' => 'heart-of-algebra', 'type' => 'mcq', 'difficulty' => 'hard',
                'prompt' => 'If $4x + 3y = 26$ and $2x + y = 10$, what is the value of $x$?',
                'options' => ['A' => '1', 'B' => '2', 'C' => '3', 'D' => '4'], 'correct_answer' => 'B',
                'explanation' => 'From the second equation, $y = 10 - 2x$. Substitute: $4x + 3(10-2x) = 26 \\implies -2x = -4 \\implies x = 2$.'],
            ['topic_slug' => 'heart-of-algebra', 'type' => 'free_response', 'difficulty' => 'hard',
                'prompt' => 'A line passes through the points $(2, 5)$ and $(4, 11)$. What is its slope?',
                'options' => null, 'correct_answer' => '3',
                'explanation' => 'Slope $= \\dfrac{11-5}{4-2} = \\dfrac{6}{2} = 3$.'],

            // Advanced Math
            ['topic_slug' => 'advanced-math', 'type' => 'mcq', 'difficulty' => 'easy',
                'prompt' => 'If $f(x) = x^2 + 1$, what is $f(3)$?',
                'options' => ['A' => '4', 'B' => '7', 'C' => '9', 'D' => '10'], 'correct_answer' => 'D',
                'explanation' => '$f(3) = 3^2 + 1 = 9 + 1 = 10$.'],
            ['topic_slug' => 'advanced-math', 'type' => 'mcq', 'difficulty' => 'easy',
                'prompt' => 'Simplify: $x^3 \\cdot x^4$',
                'options' => ['A' => '$x^7$', 'B' => '$x^{12}$', 'C' => '$2x^7$', 'D' => '$x$'], 'correct_answer' => 'A',
                'explanation' => 'When multiplying powers with the same base, add exponents: $x^{3+4} = x^7$.'],
            ['topic_slug' => 'advanced-math', 'type' => 'free_response', 'difficulty' => 'medium',
                'prompt' => 'If $f(x) = 2x^2 - 3x + 1$, what is $f(-1)$?',
                'options' => null, 'correct_answer' => '6',
                'explanation' => '$f(-1) = 2(1) - 3(-1) + 1 = 2 + 3 + 1 = 6$.'],
            ['topic_slug' => 'advanced-math', 'type' => 'mcq', 'difficulty' => 'medium',
                'prompt' => 'What is the positive solution to $x^2 - 9 = 0$?',
                'options' => ['A' => '0', 'B' => '3', 'C' => '9', 'D' => '81'], 'correct_answer' => 'B',
                'explanation' => '$x^2 = 9 \\implies x = \\pm 3$. The positive solution is $3$.'],
            ['topic_slug' => 'advanced-math', 'type' => 'free_response', 'difficulty' => 'hard',
                'prompt' => 'If $g(x) = x^2 - 6x + 9$, for what value of $x$ is $g(x) = 0$?',
                'options' => null, 'correct_answer' => '3',
                'explanation' => '$g(x) = (x-3)^2 = 0 \\implies x = 3$.'],
            ['topic_slug' => 'advanced-math', 'type' => 'mcq', 'difficulty' => 'hard',
                'prompt' => 'Simplify $\\dfrac{x^2 - 16}{x - 4}$ for $x \\neq 4$.',
                'options' => ['A' => '$x - 4$', 'B' => '$x + 4$', 'C' => '$x - 16$', 'D' => '$4$'], 'correct_answer' => 'B',
                'explanation' => 'Factor the numerator: $(x-4)(x+4)$. The $(x-4)$ terms cancel, leaving $x + 4$.'],

            // Problem Solving & Data Analysis
            ['topic_slug' => 'problem-solving', 'type' => 'mcq', 'difficulty' => 'easy',
                'prompt' => 'A recipe requires 2 cups of flour for 12 cookies. How many cups of flour are needed for 30 cookies?',
                'options' => ['A' => '3', 'B' => '4', 'C' => '5', 'D' => '6'], 'correct_answer' => 'C',
                'explanation' => '$\\dfrac{2}{12} = \\dfrac{x}{30} \\implies x = 5$.'],
            ['topic_slug' => 'problem-solving', 'type' => 'free_response', 'difficulty' => 'easy',
                'prompt' => 'A jacket originally costs $80 and is on sale for 20% off. What is the sale price in dollars?',
                'options' => null, 'correct_answer' => '64',
                'explanation' => '$80 \\times (1 - 0.20) = 80 \\times 0.8 = 64$.'],
            ['topic_slug' => 'problem-solving', 'type' => 'free_response', 'difficulty' => 'medium',
                'prompt' => 'A survey of 200 people found that 30% prefer tea. How many people prefer tea?',
                'options' => null, 'correct_answer' => '60',
                'explanation' => '$200 \\times 0.30 = 60$.'],
            ['topic_slug' => 'problem-solving', 'type' => 'free_response', 'difficulty' => 'medium',
                'prompt' => 'The average of 4 numbers is 15. If three of the numbers are 10, 12, and 18, what is the fourth number?',
                'options' => null, 'correct_answer' => '20',
                'explanation' => 'The sum of all 4 numbers is $15 \\times 4 = 60$. $60 - (10+12+18) = 20$.'],
            ['topic_slug' => 'problem-solving', 'type' => 'mcq', 'difficulty' => 'hard',
                'prompt' => 'A car travels 150 miles in 3 hours, then 100 miles in 2 hours. What is its average speed for the entire trip?',
                'options' => ['A' => '45 mph', 'B' => '48 mph', 'C' => '50 mph', 'D' => '55 mph'], 'correct_answer' => 'C',
                'explanation' => 'Total distance $= 250$ miles, total time $= 5$ hours. Average speed $= 250/5 = 50$ mph.'],
            ['topic_slug' => 'problem-solving', 'type' => 'mcq', 'difficulty' => 'hard',
                'prompt' => 'A population grows from 4,000 to 5,000 in one year. What is the percent increase?',
                'options' => ['A' => '15%', 'B' => '20%', 'C' => '25%', 'D' => '30%'], 'correct_answer' => 'C',
                'explanation' => 'Increase $= 1{,}000$. Percent increase $= 1000/4000 = 25\\%$.'],

            // Geometry
            ['topic_slug' => 'geometry', 'type' => 'mcq', 'difficulty' => 'easy',
                'prompt' => 'What is the perimeter of a rectangle with length 6 and width 4?',
                'options' => ['A' => '10', 'B' => '20', 'C' => '24', 'D' => '48'], 'correct_answer' => 'B',
                'explanation' => 'Perimeter $= 2(6+4) = 20$.'],
            ['topic_slug' => 'geometry', 'type' => 'free_response', 'difficulty' => 'easy',
                'prompt' => 'What is the area of a triangle with base 10 and height 6?',
                'options' => null, 'correct_answer' => '30',
                'explanation' => 'Area $= \\dfrac{1}{2} \\times 10 \\times 6 = 30$.'],
            ['topic_slug' => 'geometry', 'type' => 'mcq', 'difficulty' => 'medium',
                'prompt' => 'What is the circumference of a circle with radius 7? (in terms of $\\pi$)',
                'options' => ['A' => '$7\\pi$', 'B' => '$14\\pi$', 'C' => '$49\\pi$', 'D' => '$21\\pi$'], 'correct_answer' => 'B',
                'explanation' => 'Circumference $= 2\\pi r = 2\\pi(7) = 14\\pi$.',
                'desmos_expressions' => ['x^2+y^2=49']],
            ['topic_slug' => 'geometry', 'type' => 'free_response', 'difficulty' => 'medium',
                'prompt' => 'In a triangle, two angles measure 50° and 70°. What is the measure of the third angle, in degrees?',
                'options' => null, 'correct_answer' => '60',
                'explanation' => 'The angles of a triangle sum to 180°: $180 - 50 - 70 = 60$.'],
            ['topic_slug' => 'geometry', 'type' => 'free_response', 'difficulty' => 'hard',
                'prompt' => 'What is the volume of a rectangular box with length 4, width 3, and height 5?',
                'options' => null, 'correct_answer' => '60',
                'explanation' => 'Volume $= 4 \\times 3 \\times 5 = 60$.'],
            ['topic_slug' => 'geometry', 'type' => 'mcq', 'difficulty' => 'hard',
                'prompt' => 'The equation of a circle is $(x-2)^2 + (y+3)^2 = 25$. What is its center?',
                'options' => ['A' => '$(2, 3)$', 'B' => '$(2, -3)$', 'C' => '$(-2, 3)$', 'D' => '$(-2, -3)$'], 'correct_answer' => 'B',
                'explanation' => 'The standard form $(x-h)^2 + (y-k)^2 = r^2$ has center $(h,k) = (2, -3)$.',
                'desmos_expressions' => ['(x-2)^2+(y+3)^2=25']],

            // Trigonometry
            ['topic_slug' => 'trigonometry', 'type' => 'mcq', 'difficulty' => 'easy',
                'prompt' => 'In a right triangle, the side opposite angle $\\theta$ is 3 and the hypotenuse is 5. What is $\\sin(\\theta)$?',
                'options' => ['A' => '3/4', 'B' => '3/5', 'C' => '4/5', 'D' => '5/3'], 'correct_answer' => 'B',
                'explanation' => '$\\sin(\\theta) = \\dfrac{\\text{opposite}}{\\text{hypotenuse}} = \\dfrac{3}{5}$.'],
            ['topic_slug' => 'trigonometry', 'type' => 'free_response', 'difficulty' => 'easy',
                'prompt' => 'If $\\cos(\\theta) = 1$ and $0° \\le \\theta \\le 90°$, what is $\\theta$ in degrees?',
                'options' => null, 'correct_answer' => '0',
                'explanation' => '$\\cos(0°) = 1$, so $\\theta = 0°$.'],
            ['topic_slug' => 'trigonometry', 'type' => 'free_response', 'difficulty' => 'medium',
                'prompt' => 'If $\\tan(\\theta) = 1$ and $0° \\le \\theta \\le 90°$, what is $\\theta$ in degrees?',
                'options' => null, 'correct_answer' => '45',
                'explanation' => '$\\tan(45°) = 1$.'],
            ['topic_slug' => 'trigonometry', 'type' => 'mcq', 'difficulty' => 'medium',
                'prompt' => 'In a right triangle, the side adjacent to angle $\\theta$ is 6 and the hypotenuse is 10. What is $\\cos(\\theta)$?',
                'options' => ['A' => '3/5', 'B' => '4/5', 'C' => '3/4', 'D' => '5/3'], 'correct_answer' => 'A',
                'explanation' => '$\\cos(\\theta) = \\dfrac{\\text{adjacent}}{\\text{hypotenuse}} = \\dfrac{6}{10} = \\dfrac{3}{5}$.'],
            ['topic_slug' => 'trigonometry', 'type' => 'mcq', 'difficulty' => 'hard',
                'prompt' => 'If $\\sin(\\theta) = 0.6$ and $\\theta$ is acute, what is $\\sin(90° - \\theta)$?',
                'options' => ['A' => '0.4', 'B' => '0.6', 'C' => '0.8', 'D' => '1.0'], 'correct_answer' => 'C',
                'explanation' => '$\\sin(90°-\\theta) = \\cos(\\theta)$. Since $\\sin^2+\\cos^2=1$, $\\cos(\\theta) = 0.8$.'],
            ['topic_slug' => 'trigonometry', 'type' => 'mcq', 'difficulty' => 'hard',
                'prompt' => 'Simplify: $\\sin^2(x) + \\cos^2(x)$',
                'options' => ['A' => '0', 'B' => '1', 'C' => '2', 'D' => '$\\sin(2x)$'], 'correct_answer' => 'B',
                'explanation' => 'This is the Pythagorean identity, which always equals 1.'],

            // Functions
            ['topic_slug' => 'functions', 'type' => 'free_response', 'difficulty' => 'easy',
                'prompt' => 'If $f(x) = 5x - 2$, what is $f(4)$?',
                'options' => null, 'correct_answer' => '18',
                'explanation' => '$f(4) = 5(4) - 2 = 20 - 2 = 18$.'],
            ['topic_slug' => 'functions', 'type' => 'free_response', 'difficulty' => 'easy',
                'prompt' => 'If $h(x) = x + 7$, for what value of $x$ is $h(x) = 15$?',
                'options' => null, 'correct_answer' => '8',
                'explanation' => '$x + 7 = 15 \\implies x = 8$.'],
            ['topic_slug' => 'functions', 'type' => 'free_response', 'difficulty' => 'medium',
                'prompt' => 'If $f(x) = x^2$ and $g(x) = x - 1$, what is $f(g(4))$?',
                'options' => null, 'correct_answer' => '9',
                'explanation' => '$g(4) = 4 - 1 = 3$. Then $f(3) = 3^2 = 9$.'],
            ['topic_slug' => 'functions', 'type' => 'mcq', 'difficulty' => 'medium',
                'prompt' => 'For which value of $x$ is the function $f(x) = \\dfrac{1}{x-5}$ undefined?',
                'options' => ['A' => '0', 'B' => '1', 'C' => '5', 'D' => '-5'], 'correct_answer' => 'C',
                'explanation' => 'The denominator cannot equal 0, so $x \\neq 5$; the function is undefined at $x=5$.'],
            ['topic_slug' => 'functions', 'type' => 'free_response', 'difficulty' => 'hard',
                'prompt' => 'If $f(x) = 2^x$, what is $f(3) - f(1)$?',
                'options' => null, 'correct_answer' => '6',
                'explanation' => '$f(3) = 8$ and $f(1) = 2$. $8 - 2 = 6$.'],
            ['topic_slug' => 'functions', 'type' => 'mcq', 'difficulty' => 'hard',
                'prompt' => 'The graph of $y = (x-2)(x+4)$ crosses the x-axis at which points?',
                'options' => ['A' => '$x=2$ and $x=4$', 'B' => '$x=2$ and $x=-4$', 'C' => '$x=-2$ and $x=4$', 'D' => '$x=-2$ and $x=-4$'], 'correct_answer' => 'B',
                'explanation' => 'Setting each factor to 0: $x - 2 = 0 \\implies x=2$; $x+4=0 \\implies x=-4$.',
                'desmos_expressions' => ['y=(x-2)(x+4)']],

            // Statistics
            ['topic_slug' => 'statistics', 'type' => 'free_response', 'difficulty' => 'easy',
                'prompt' => 'What is the median of the data set $\\{3, 7, 9, 12, 15\\}$?',
                'options' => null, 'correct_answer' => '9',
                'explanation' => 'When sorted, the middle value of 5 numbers is the 3rd value: 9.'],
            ['topic_slug' => 'statistics', 'type' => 'free_response', 'difficulty' => 'easy',
                'prompt' => 'What is the mode of the data set $\\{2, 3, 3, 5, 7, 3\\}$?',
                'options' => null, 'correct_answer' => '3',
                'explanation' => '3 appears most frequently (three times).'],
            ['topic_slug' => 'statistics', 'type' => 'free_response', 'difficulty' => 'medium',
                'prompt' => 'If the mean of 6 numbers is 10, what is their sum?',
                'options' => null, 'correct_answer' => '60',
                'explanation' => 'Sum $=$ mean $\\times$ count $= 10 \\times 6 = 60$.'],
            ['topic_slug' => 'statistics', 'type' => 'free_response', 'difficulty' => 'medium',
                'prompt' => 'A data set has a range of 25 and a minimum value of 10. What is the maximum value?',
                'options' => null, 'correct_answer' => '35',
                'explanation' => 'Range $=$ max $-$ min, so max $= 25 + 10 = 35$.'],
            ['topic_slug' => 'statistics', 'type' => 'mcq', 'difficulty' => 'hard',
                'prompt' => 'Which data set has the largest range: A) $\\{1,2,3,4,5\\}$ or B) $\\{0,10,20\\}$?',
                'options' => ['A' => 'Set A', 'B' => 'Set B', 'C' => 'They are equal', 'D' => 'Cannot be determined'], 'correct_answer' => 'B',
                'explanation' => 'Range of A is $5-1=4$. Range of B is $20-0=20$. Set B has the larger range.'],
            ['topic_slug' => 'statistics', 'type' => 'mcq', 'difficulty' => 'hard',
                'prompt' => "If a data set's standard deviation is 0, what must be true about the data?",
                'options' => ['A' => 'All values are 0', 'B' => 'All values are equal', 'C' => 'The mean is 0', 'D' => 'The data has no median'], 'correct_answer' => 'B',
                'explanation' => 'Standard deviation of 0 means there is no spread from the mean — every value is identical.'],
        ];

        foreach ($questions as $q) {
            $topic = $topics->get($q['topic_slug']);
            if (!$topic) {
                continue;
            }

            unset($q['topic_slug']);
            $q['topic_id'] = $topic->id;
            $q['is_diagnostic'] = false;
            $q['options'] = isset($q['options']) ? json_encode($q['options']) : null;
            $q['desmos_expressions'] = isset($q['desmos_expressions']) ? json_encode($q['desmos_expressions']) : null;

            Question::create($q);
        }

        $this->command?->info('✓ Practice questions seeded.');
    }
}
