<?php

namespace App\Services;

use App\Repositories\Student\StudentRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;
use Weidner\Goutte\GoutteFacade;

class CrawlDataLearningOutcomeService
{
    public function __construct(
        private StudentRepositoryInterface $studentRepository,
    )
    {
    }

    public function crawlData($studentCode): bool
    {
        DB::beginTransaction();
        try {
            $url = "http://daotao.vnua.edu.vn/Default.aspx?page=xemdiemthi&id={$studentCode}";
            $crawler = GoutteFacade::request('GET', $url);
            $dataCrawler = [];

            $selectors = $crawler->filter('.view-table tr');
            $key = -1;
            foreach ($selectors as $selector) {
                $domElement = new Crawler($selector);
                $className = $domElement->attr('class');
                if ($className == 'title-hk-diem') {
                    $key++;
                    $dataCrawler[$key]['title-hk-diem'] = $domElement->text();
                }

                if ($className == 'row-diem') {
                    $dataCrawler[$key]['row-diem'][] = $domElement->children()->each(function ($element) {
                        return $element->text();
                    });
                }

                if ($className == 'row-diemTK') {
                    $dataCrawler[$key]['row-diemTK'][] = $domElement->filter('span')->eq(1)->text();
                }

            }
            $dataProcesses = [];
            if (!empty($dataCrawler)) {
                foreach ($dataCrawler as $data) {
                    $dataProcess = $this->processDataCrawler($data);
                    $dataProcesses[] = $dataProcess;
                }
            }
            $student = $this->studentRepository->getFirstBy(['student_code' => $studentCode]);
            foreach ($dataProcesses as $process) {
                $learningOutcomes = $student->learningOutcomes()->updateOrCreate([
                    'semester' => $process['semester'],
                    'year_school_start' => $process['year_school_start'],
                    'year_school_end' => $process['year_school_end']
                ], $process);

                if (!empty($process['detail'])) {
                    foreach ($process['detail'] as $detail) {
                        $learningOutcomes->detail()->updateOrCreate([
                            'order' => $detail['order']
                        ], $detail);
                    }
                }
            }


            DB::commit();
            return true;
        }catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error service crawl data learning', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return false;
        }

    }

    private function processDataCrawler(array $dataCrawler): array
    {
        $dataProcessed = [];
        $dataTitle = $this->handleProcessTitle($dataCrawler['title-hk-diem']);
        $dataProcessed['semester'] = @$dataTitle[0];
        $dataProcessed['year_school_start'] = @$dataTitle[1];
        $dataProcessed['year_school_end'] = @$dataTitle[2];
        if (!empty($dataCrawler['row-diemTK'])) {
            $dataProcessed['semester_average_10'] = @$dataCrawler['row-diemTK'][0];
            $dataProcessed['semester_average_4'] = @$dataCrawler['row-diemTK'][1];
            $dataProcessed['cumulative_average_10'] = @$dataCrawler['row-diemTK'][2];
            $dataProcessed['cumulative_average_4'] = @$dataCrawler['row-diemTK'][3];
            $dataProcessed['credits'] = @$dataCrawler['row-diemTK'][4];
            $dataProcessed['cumulative_credits'] = @$dataCrawler['row-diemTK'][5];
            $dataProcessed['type_average'] = @$dataCrawler['row-diemTK'][6];
        }
        $dataProcessed['detail'] = [];
        if (!empty($dataCrawler['row-diem'])) {
            foreach ($dataCrawler['row-diem'] as $dataPoint) {
                $pointSubject = [];
                $pointSubject['order'] = @$dataPoint[0];
                $pointSubject['subject_code'] = @$dataPoint[1];
                $pointSubject['subject_name'] = @$dataPoint[2];
                $pointSubject['credits'] = @$dataPoint[3];
                $pointSubject['precent_test'] = @$dataPoint[4];
                $pointSubject['precent_exam'] = @$dataPoint[5];
                $pointSubject['diligence_point'] = @(float)$dataPoint[6];
                $pointSubject['progress_point'] = @(float)$dataPoint[7];
                $pointSubject['exam_point'] = @(float)$dataPoint[8];
                $pointSubject['total_point_number'] = @(float)$dataPoint[9];
                $pointSubject['total_point_string'] = @$dataPoint[11];
                $dataProcessed['detail'][] = $pointSubject;
            }
        }

        return $dataProcessed;
    }

    private function handleProcessTitle($title): array
    {
        $title = str_replace("Học kỳ", "", $title);
        $title = str_replace("Năm học", "", $title);
        $title = str_replace(" ", "", $title);
        return explode('-', $title);
    }
}
