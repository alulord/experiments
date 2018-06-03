<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AnalyticsDashboardController extends Controller
{
    /**
     * @Route("/analytics", name="analytics_dashboard")
     */
    public function index()
    {
        $ga = $this->get('google_analytics_api.api');
        $ga->getClient()->fetchAccessTokenWithAssertion();
        $data = $ga->getDataDateRangeMetricsDimensions(
            'ga:136287816',    // viewid
            '30daysAgo',   // date start
            'today',        // date end
            ['sessions','users','percentNewSessions','bounceRate'],             // metric
            ['source','campaign','fullReferrer','sourceMedium','pagePath'],     // dimension
            [   // order metric and/or dimension
                'fields'    =>  ['sessions'],
                'order'     =>  'descending'
            ],
            [   // metric
                'metric_name'       =>  'sessions',
                'operator'          =>  'LESS_THAN',
                'comparison_value'  =>  '100'
            ]
        );

        $accessToken = $ga->getClient()->getAccessToken();
        dump($data);

        return $this->render(
            'analytics_dashboard/index.html.twig',
            [
                'controller_name' => 'AnalyticsDashboardController',
                'ga_access_token' => $accessToken['access_token'],
            ]
        );
    }
}
