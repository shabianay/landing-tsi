<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ServiceIconTest extends TestCase
{
    private function iconFor(string $title): string
    {
        return Blade::render('<x-service-icon :title="$t" />', ['t' => $title]);
    }

    public function test_icons_follow_the_service_title(): void
    {
        $this->assertStringContainsString('M17.25 6.75 22.5 12', $this->iconFor('Web Development'));
        $this->assertStringContainsString('M2.25 7.125', $this->iconFor('UI/UX Design'));
        $this->assertStringContainsString('M2.25 7.125', $this->iconFor('Desain Website'));
        $this->assertStringContainsString('M10.5 18a7.5', $this->iconFor('SEO Optimization'));
        $this->assertStringContainsString('M10.34 15.84', $this->iconFor('Digital Marketing'));
        $this->assertStringContainsString('M10.5 1.5H8.25', $this->iconFor('Aplikasi Mobile'));
    }
}
