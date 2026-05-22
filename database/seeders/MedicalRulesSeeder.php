<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicalRulesSeeder extends Seeder
{
    public function run(): void
    {
        // Clear old data to prevent duplication
        DB::table('comparison_rules')->delete();
        DB::table('recommendation_rules')->delete();

        /*
        |--------------------------------------------------------------------------
        | 1. Comparison Rules (Critical Summary)
        |--------------------------------------------------------------------------
        */
        $comparisonRules = [
            [
                'rule_key' => 'significant_worsening',
                'min_value' => 50.00,
                'max_value' => null, // +50% or more
                'status' => 'Needs Monitoring',
                'message_ar' => 'Significant increase in episodes. Close monitoring is recommended and medical review may be needed.',
                'message_en' => 'Significant increase in episodes. Close monitoring is recommended and medical review may be needed.',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'rule_key' => 'noticeable_increase',
                'min_value' => 10.00,
                'max_value' => 49.99, // 10% to 49%
                'status' => 'Slight Worsening',
                'message_ar' => 'Noticeable increase compared with the previous period. Review possible triggers and monitor closely.',
                'message_en' => 'Noticeable increase compared with the previous period. Review possible triggers and monitor closely.',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'rule_key' => 'stable_condition',
                'min_value' => -9.99,
                'max_value' => 9.99, // -9% to +9%
                'status' => 'Stable',
                'message_ar' => 'Condition appears stable compared with the previous period. Continue regular monitoring.',
                'message_en' => 'Condition appears stable compared with the previous period. Continue regular monitoring.',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'rule_key' => 'noticeable_improvement',
                'min_value' => -49.99,
                'max_value' => -10.00, // -10% to -49%
                'status' => 'Improving',
                'message_ar' => 'Noticeable improvement compared with the previous period. Continue the current support plan.',
                'message_en' => 'Noticeable improvement compared with the previous period. Continue the current support plan.',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'rule_key' => 'significant_improvement',
                'min_value' => null, // -50% or less
                'max_value' => -50.00, 
                'status' => 'Significant Improvement',
                'message_ar' => 'Significant improvement compared with the previous period. Continue monitoring and review positive factors with the doctor.',
                'message_en' => 'Significant improvement compared with the previous period. Continue monitoring and review positive factors with the doctor.',
                'created_at' => now(), 'updated_at' => now()
            ],
        ];
        DB::table('comparison_rules')->insert($comparisonRules);

        /*
        |--------------------------------------------------------------------------
        | 2. Recommendation Rules (Medical Advice)
        |--------------------------------------------------------------------------
        */
        $recommendationRules = [
            [
                'rule_key' => 'high_episodes_increase',
                'title' => 'Significant Episode Increase',
                'message' => 'Significant increase in episodes. Strict monitoring is advised, and a medical review is highly recommended.',
                'priority' => 'critical',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'rule_key' => 'noticeable_episodes_increase',
                'title' => 'Noticeable Episode Increase',
                'message' => 'Noticeable increase detected. Please review potential environmental triggers like noise, lighting, or routine changes.',
                'priority' => 'high',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'rule_key' => 'long_episode_duration',
                'title' => 'Prolonged Episode Duration',
                'message' => 'Episode duration is unusually long. Please log the details and discuss them with your healthcare provider.',
                'priority' => 'medium',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'rule_key' => 'high_hr_low_activity',
                'title' => 'High Resting Heart Rate',
                'message' => 'High heart rate detected during low activity. Please monitor closely and re-measure.',
                'priority' => 'high',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'rule_key' => 'safe_zone_exit',
                'title' => 'Safe Zone Breach',
                'message' => 'Child has left the designated safe zone. Verify their location and safety immediately.',
                'priority' => 'critical',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'rule_key' => 'panic_status',
                'title' => 'Panic or Severe Anxiety',
                'message' => 'Panic signs detected. Move the child to a calm environment, reduce sensory triggers, and intervene quickly.',
                'priority' => 'critical',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'rule_key' => 'stable_condition',
                'title' => 'Stable Condition',
                'message' => 'Vital signs are normal and stable. Continue the current routine to maintain stability.',
                'priority' => 'low',
                'created_at' => now(), 'updated_at' => now()
            ],
        ];
        DB::table('recommendation_rules')->insert($recommendationRules);
    }
}
