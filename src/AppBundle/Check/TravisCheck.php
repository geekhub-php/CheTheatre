<?php

namespace AppBundle\Check;

use ZendDiagnostics\Check\CheckInterface;
use GuzzleHttp\Client as Guzzle;
use ZendDiagnostics\Result\Failure;
use ZendDiagnostics\Result\Success;

/**
 * Class TravisCheck
 * @package AppBundle\Check
 */
class TravisCheck implements CheckInterface
{
    private $account;

    private $repository;

    private $branch;

    /**
     * @param $account
     * @param $repository
     * @param $branch
     */
    public function __construct($account, $repository, $branch)
    {
        $this->account = $account;
        $this->repository = $repository;
        $this->branch = $branch;
    }

    /**
     * @return Failure|\ZendDiagnostics\Result\ResultInterface|Success
     */
    public function check()
    {
        $client = new Guzzle();

        $url = sprintf('https://api.travis-ci.org/repos/%s/%s/branches/%s', $this->account, $this->repository, $this->branch);

        $res = $client->get($url);

        $json = $res->json();

        switch ($json['branch']['state']) {
            case 'passed':
                $result = new Success('All tests passed');
                break;
            case 'failed':
                $result = new Failure('Tests not passed');
                break;
            case 'errored':
                $result = new Failure('Error in code');
                break;
            default:
                $result = new Failure('Unknown error');
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'Travis';
    }
}
