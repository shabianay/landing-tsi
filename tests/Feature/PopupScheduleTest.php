<?php

namespace Tests\Feature;

use App\Models\Popup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PopupScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function popup(array $attributes = []): Popup
    {
        return Popup::create(array_merge([
            'title' => 'Promo Akhir Tahun',
            'content' => 'Diskon pembuatan website.',
            'is_active' => true,
            'display_delay' => 0,
        ], $attributes));
    }

    public function test_schedule_is_read_as_wib_wall_clock_time(): void
    {
        // 10:00 UTC = 17:00 WIB. A popup the admin scheduled from 15:00 WIB has already started.
        Carbon::setTestNow(Carbon::parse('2026-11-27 10:00:00', 'UTC'));
        $popup = $this->popup(['start_at' => '2026-11-27 15:00:00', 'end_at' => '2026-11-27 23:59:00']);

        $this->assertSame('live', $popup->visibility());
        $this->get(route('home'))->assertOk()->assertSee('Promo Akhir Tahun');
    }

    public function test_admin_sees_why_an_active_popup_is_not_shown(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-27 10:00:00', 'UTC'));

        $this->assertSame('scheduled', $this->popup(['start_at' => '2026-11-28 08:00:00'])->visibility());
        $this->assertSame('ended', $this->popup(['end_at' => '2026-11-27 12:00:00'])->visibility());
        $this->assertSame('inactive', $this->popup(['is_active' => false])->visibility());
        $this->assertSame('live', $this->popup()->visibility());
        $this->assertSame(1, Popup::active()->count());
    }
}
