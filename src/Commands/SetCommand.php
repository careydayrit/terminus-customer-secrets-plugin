<?php

namespace Pantheon\TerminusSecretsManager\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;
use Pantheon\Terminus\Exceptions\TerminusNotFoundException;
use Pantheon\Terminus\Exceptions\TerminusException;
use Pantheon\TerminusSecretsManager\SecretsApi\SecretsApiAwareTrait;
use Pantheon\Terminus\Site\SiteAwareTrait;
use Pantheon\Terminus\Site\SiteAwareInterface;
use Consolidation\OutputFormatters\StructuredData\RowsOfFields;

/**
 * Class SetCommand
 * Set secret for a given site.
 *
 * @package Pantheon\Terminus\Commands\CustomerSecrets
 */
class SetCommand extends SecretBaseCommand implements SiteAwareInterface
{
    use SiteAwareTrait;

    /**
     * Set secret for a specific site.
     *
     * @authorize
     *
     * @command secret:set
     * @aliases secret-set
     *
     * @option string $type Secret type
     * @option array $scope Secret scope
     * @option boolean $debug Run command in debug mode
     * @param string $site_id The name or UUID of a site to retrieve information on
     * @param string $name The secret name
     * @param string $value The secret value
     * @param array $options
     *
     * @usage <site> <name> <value> Set secret <name> with value <value>.
     * @usage <site> <name> <value> --debug Set given secret (debug mode).
     *
     * @throws \Pantheon\Terminus\Exceptions\TerminusException
     */
    public function setSecret($site_id, string $name, string $value, array $options = [
        'type' => 'env',
        'scope' => ['user'],
        'debug' => false,
    ])
    {
        $site = $this->getSite($site_id);
        $this->setupRequest();
        if ($this->secretsApi->setSecret(
            $site->id,
            $name,
            $value,
            $options['type'],
            $options['scope'],
            $options['debug']
        )) {
            $this->log()->notice('Success');
        } else {
            $this->log()->error('An error happened when trying to set the secret.');
        }
    }
}
