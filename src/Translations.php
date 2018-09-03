<?php

namespace PhraseAppPHP;

use GuzzleHttp\Client;

class Translations
{
    /** @var Client $client */
    private $client;

    public function __construct()
    {
        $config = Config::get('phraseapp');

        $this->client = new Client([
            'base_uri' => $config['url'] . $config['project_id'] . '/',
            'auth' => [$config['access_token']]
        ]);
    }

    /**
     * @param string $id
     * @param string $file
     * @throws \Exception
     */
    public function get($id, $file)
    {
        $result = $this->client->get('locales/' . $id . '/download?file_format=json');

        if ($result->getStatusCode() !== 200) {
            throw new \Exception('Cannot get translations');
        }

        $data = $this->normalizeArray(json_decode($result->getBody()->getContents(), true));

        $this->writeFile($data, $file);
    }

    /**
     * @param array $data
     * @return array
     */
    private function normalizeArray(array $data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (!$value['message']) {
                continue;
            }

            $result[$key] = $value['message'];
        }

        return $result;
    }

    /**
     * @param array $data
     * @param string $file
     */
    private function writeFile($data, $file)
    {
        $fileContent = "<?php\n";
        $fileContent .= "return [\n";
        foreach ($data as $key => $value) {
            $fileContent .= '   "' . addslashes($key) . '" => "' . addslashes($value) . '",' . "\n";
        }
        $fileContent .= "];\n";

        $fp = fopen($file,'w');
        fwrite($fp, $fileContent);
        fclose($fp);
    }
}
