<?php

namespace Drupal\matomobpm;

use \Drupal\Core\Http\ClientFactory;
use Exception;

class MatomobpmAPIConnector {
    private $client;
    private $query;

    private $url;
    private $id_site;

    public function __construct(ClientFactory $client) {
        $matomobpm_config = \Drupal::state()->get(\Drupal\matomobpm\Form\MatomobpmApiConfigForm::MATOMOBPM_API_CONFIG_FORM);
        $api_url = ($matomobpm_config['matomobpm_api_base_url']) ?: '';
        $api_token = ($matomobpm_config['matomobpm_api_token']) ?: '';
        $id_site = ($matomobpm_config['id_site']) ?: '';
        
        $query = ['matomobpm_api_token' => $api_token];

        $this->query = $query;
        $this->url = $api_url;
        $this->id_site = $id_site;
        $this->client = $client->fromOptions([
            'base_uri' => $api_url
        ]);
    }

    public function getVisitsSummary($period = 'day', $date = 'today') {
        $data = [];
        try{
            $response = $this->client->get('', [
                'query' => [
                    'module' => 'API',
                    'method' => 'Actions.getPageTitles',
                    'idSite' => $this->id_site,
                    'period' => $period,
                    'date' => $date,
                    'format' => 'json',
                    'token_auth' => $this->query['matomobpm_api_token'],
                ],
            ]);
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                // \Drupal::logger('matomobpm')->notice('Matomo API request succeeded with status code: @code', ['@code' => $response->getStatusCode()]);
                return $data;
            } else {
                // Gérer l'erreur ici, par exemple en enregistrant le code de statut dans les logs.
                \Drupal::logger('matomobpm')->error('Matomo API request failed with status code: @code', ['@code' => $response->getStatusCode()]);
                return [];
            }
        }catch (Exception $e) {
            \Drupal::logger('matomobpm')->error('Matomo API request failed with error: @error', ['@error' => $e->getMessage()]);
        }
        return $data;
        
    }

    public function getSparklineImages() {
        $url = $this->url;
        $key = $this->query['matomobpm_api_token'];
        $imageUrls = array(

          'visits' =>  $url . "/?period=day&date=last7&forceView=1&viewDataTable=sparkline&module=API&action=get&disableLink=0&widget=1&idSite=". $this->id_site ."&columns=nb_visits%2Cnb_uniq_visitors&token_auth=" . $key,  // Remplacez par votre URL réelle
          'length' => $url . "/?period=day&date=last7&forceView=1&viewDataTable=sparkline&module=API&action=get&disableLink=0&widget=1&idSite=". $this->id_site ."&columns=avg_time_on_site&token_auth=" . $key,
          'bounce' => $url . "/?period=day&date=last7&forceView=1&viewDataTable=sparkline&module=API&action=get&disableLink=0&widget=1&idSite=". $this->id_site ."&columns=bounce_rate&token_auth=" . $key,
          'actions' => $url . "/?period=day&date=last7&forceView=1&viewDataTable=sparkline&module=API&action=get&disableLink=0&widget=1&idSite=". $this->id_site ."&columns=nb_actions_per_visit&token_auth=" . $key,
          'maxActions' => $url . "/?period=day&date=last7&forceView=1&viewDataTable=sparkline&module=API&action=get&disableLink=0&widget=1&idSite=". $this->id_site ."&columns=max_actions&token_auth=" . $key,
          'pages' => $url . "/?period=day&date=last7&forceView=1&viewDataTable=sparkline&module=API&action=get&disableLink=0&widget=1&idSite=". $this->id_site ."&columns=nb_pageviews%2Cnb_uniq_pageviews&token_auth=" . $key,
          'downloads' => $url . "/?period=day&date=last7&forceView=1&viewDataTable=sparkline&module=API&action=get&disableLink=0&widget=1&idSite=". $this->id_site ."&columns=nb_downloads%2Cnb_uniq_downloads&token_auth=" . $key,
          'links' => $url . "/?period=day&date=last7&forceView=1&viewDataTable=sparkline&module=API&action=get&disableLink=0&widget=1&idSite=". $this->id_site ."&columns=nb_outlinks%2Cnb_uniq_outlinks&token_auth=" . $key,
          
        );
      
        return $imageUrls;
      }

    public function getSparklineInfos($period = 'day', $date = 'today') {
        $data = [];
        try{
            $response = $this->client->get('', [
                'query' => [
                    'module' => 'API',
                    'method' => 'VisitsSummary.get',
                    'idSite' => $this->id_site,
                    'period' => $period,
                    'date' => $date,
                    'format' => 'json',
                    'token_auth' => $this->query['matomobpm_api_token'],
                ],
            ]);
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                // \Drupal::logger('matomobpm')->notice('Matomo API request succeeded with status code: @code', ['@code' => $response->getStatusCode()]);
                return $data;
            } else {
                // Gérer l'erreur ici, par exemple en enregistrant le code de statut dans les logs.
                \Drupal::logger('matomobpm')->error('Matomo API request failed with status code: @code', ['@code' => $response->getStatusCode()]);
                return [];
            }
        }catch (Exception $e) {
            \Drupal::logger('matomobpm')->error('Matomo API request failed with error: @error', ['@error' => $e->getMessage()]);
        }
        return $data;
    }
    public function getSparklineInfos2($period = 'day', $date = 'today') {
        $data = [];
        try{
            $response = $this->client->get('', [
                'query' => [
                    'module' => 'API',
                    'method' => 'Actions.get',
                    'idSite' => $this->id_site,
                    'period' => $period,
                    'date' => $date,
                    'format' => 'json',
                    'token_auth' => $this->query['matomobpm_api_token'],
                ],
            ]);
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                // \Drupal::logger('matomobpm')->notice('Matomo API request succeeded with status code: @code', ['@code' => $response->getStatusCode()]);
                return $data;
            } else {
                // Gérer l'erreur ici, par exemple en enregistrant le code de statut dans les logs.
                \Drupal::logger('matomobpm')->error('Matomo API request failed with status code: @code', ['@code' => $response->getStatusCode()]);
                return [];
            }
        }catch (Exception $e) {
            \Drupal::logger('matomobpm')->error('Matomo API request failed with error: @error', ['@error' => $e->getMessage()]);
        }
        return $data;
    }

    public function getReferrerType($period = 'day', $date = 'today') {
        $data = [];
        try{
            $response = $this->client->get('', [
                'query' => [
                    'module' => 'API',
                    'method' => 'Referrers.getReferrerType',
                    'idSite' => $this->id_site,
                    'period' => $period,
                    'date' => $date,
                    'format' => 'json',
                    'token_auth' => $this->query['matomobpm_api_token'],
                ],
            ]);
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                // \Drupal::logger('matomobpm')->notice('Matomo API request succeeded with status code: @code', ['@code' => $response->getStatusCode()]);
                return $data;
            } else {
                // Gérer l'erreur ici, par exemple en enregistrant le code de statut dans les logs.
                \Drupal::logger('matomobpm')->error('Matomo API request failed with status code: @code', ['@code' => $response->getStatusCode()]);
                return [];
            }
        }catch (Exception $e) {
            \Drupal::logger('matomobpm')->error('Matomo API request failed with error: @error', ['@error' => $e->getMessage()]);
        }
        return $data;
    }
    public function getDevicesType($period = 'day', $date = 'today') {
        $data = [];
        try{
            $response = $this->client->get('', [
                'query' => [
                    'module' => 'API',
                    'method' => 'DevicesDetection.getType',
                    'idSite' => $this->id_site,
                    'period' => $period,
                    'date' => $date,
                    'format' => 'json',
                    'token_auth' => $this->query['matomobpm_api_token'],
                ],
            ]);
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                // \Drupal::logger('matomobpm')->notice('Matomo API request succeeded with status code: @code', ['@code' => $response->getStatusCode()]);
                return $data;
            } else {
                // Gérer l'erreur ici, par exemple en enregistrant le code de statut dans les logs.
                \Drupal::logger('matomobpm')->error('Matomo API request failed with status code: @code', ['@code' => $response->getStatusCode()]);
                return [];
            }
        }catch (Exception $e) {
            \Drupal::logger('matomobpm')->error('Matomo API request failed with error: @error', ['@error' => $e->getMessage()]);
        }
        return $data;
    }
    public function getMapData($period = 'day', $date = 'today') {
        $data = [];
        try{
            $response = $this->client->get('', [
                'query' => [
                    'module' => 'API',
                    'method' => 'UserCountry.getCity',
                    'idSite' => $this->id_site,
                    'period' => $period,
                    'date' => $date,
                    'format' => 'json',
                    'token_auth' => $this->query['matomobpm_api_token'],
                ],
            ]);
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                // \Drupal::logger('matomobpm')->notice('Matomo API request succeeded with status code: @code', ['@code' => $response->getStatusCode()]);
                return $data;
            } else {
                // Gérer l'erreur ici, par exemple en enregistrant le code de statut dans les logs.
                \Drupal::logger('matomobpm')->error('Matomo API request failed with status code: @code', ['@code' => $response->getStatusCode()]);
                return [];
            }
        }catch (Exception $e) {
            \Drupal::logger('matomobpm')->error('Matomo API request failed with error: @error', ['@error' => $e->getMessage()]);
        }
        return $data;
    }
}