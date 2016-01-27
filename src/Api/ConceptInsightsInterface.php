<?php

namespace Brideo\IbmWatson\Ibm\Api;

use Psr\Http\Message\ResponseInterface;

interface ConceptInsightsInterface
{
    /**
     * Create a new object in IBM Concept Insights
     *
     * @param bool $name
     *
     * @return mixed|ResponseInterface
     */
    public function create($name = false);

    /**
     * Retrieve a new object in IBM Concept Insights.
     *
     * @param bool $name
     *
     * @return mixed|ResponseInterface
     */
    public function retrieve($name = false);

    /**
     * Delete a new object in IBM Concept Insights.
     *
     * @param bool $name
     *
     * @return mixed|ResponseInterface
     */
    public function delete($name = false);

    /**
     * Update a new object in IBM Concept Insights.
     *
     * @param bool $name
     *
     * @return mixed|ResponseInterface
     */
    public function update($name = false);

}
