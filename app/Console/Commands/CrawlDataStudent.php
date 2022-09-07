<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Weidner\Goutte\GoutteFacade;

class CrawlDataStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:student';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $crawler = GoutteFacade::request('GET', 'http://daotao.vnua.edu.vn/Default.aspx?page=xemdiemthi&id=637944');
        $crawler->filter('.view-table')->each(function ($node) {
            $point = $node->filter('.row-diem');
            $semester = $node->filter('.title-hk-diem');

            $semesters = $semester->each(function ($s) use ($point) {
                $s->point = $point->each(function ($p) {

                    return $p->text();
                });
                return $s;
            });


            dd($semesters);
        });

        return 0;
    }
}
