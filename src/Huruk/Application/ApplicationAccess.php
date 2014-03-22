<?php
/**
 *
 * User: migue
 * Date: 22/02/14
 * Time: 16:20
 */

namespace Huruk\Application;


interface ApplicationAccess
{
    /**
     * @param ApplicationInterface $app
     * @return void
     */
    public function setApplication(ApplicationInterface $app);

    /**
     * @return ApplicationInterface
     */
    public function getApplication();
}
