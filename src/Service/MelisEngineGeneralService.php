<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\EventManager\EventManagerInterface;
use MelisCore\Service\MelisServiceManager;

/**
 * 
 * This service handles the generic service system of Melis.
 *
 */
class MelisEngineGeneralService extends MelisServiceManager implements EventManagerAwareInterface
{
	public $eventManager;

	public function setEventManager(EventManagerInterface $eventManager): void
	{
		$this->eventManager = $eventManager;
	}
	
	/**
     * @return array
     */
	public function getEventManager()
	{
		return $this->eventManager;
	}

	public function getRenderMode()
	{
		$router = $this->getServiceManager()->get('router');
		$request = $this->getServiceManager()->get('request');
	
		$routeMatch = $router->match($request);
		$renderMode = $routeMatch->getParam('renderMode', 'melis');
		
		return $renderMode;
	}
	
	/**
		* This method creates an array from the parameters, using parameters' name as keys
		* and taking values or default values.
		* It is used to prepare args for events listening.
		* 
		* @param string $class_method
		* @param mixed[] $parameterValues
		*/
	public function makeArrayFromParameters($class_method, $parameterValues)
	{
		if (empty($class_method))
			return array();
		
		// Get the class name and the method name
		list($className, $methodName) = explode('::', $class_method);
		if (empty($className) || empty($methodName))
			return array();
		
		/**
			* Build an array from the parameters
			* Parameters' name will become keys
			* Values will be parameters' values or default values
			*/ 
		$parametersArray = array();
		try 
		{
			// Gets the data of class/method from Reflection object
			$reflectionMethod = new \ReflectionMethod($className, $methodName);
			$parameters = $reflectionMethod->getParameters();
			
			// Loop on parameters
			foreach ($parameters as $keyParameter => $parameter)
			{
				// Check if we have a value given
				if (!empty($parameterValues[$keyParameter]))
					$parametersArray[$parameter->getName()] = $parameterValues[$keyParameter];
					else
						// No value given, check if parameter has an optional value
						if ($parameter->isOptional())
							$parametersArray[$parameter->getName()] = $parameter->getDefaultValue();
							else
								// Else
								$parametersArray[$parameter->getName()] = null;
			}
		}
		catch (\Exception $e)
		{
			// Class or method were not found
			return array();
		}
		
		return $parametersArray;
	}
	
	public function sendEvent($eventName, $parameters, $target = null)
	{
		if (is_null($target))
			$target = $this;
			
		$parameters = $this->eventManager->prepareArgs($parameters);
		$this->eventManager->trigger($eventName, $target, $parameters);
        if(!empty($parameters))
            $parameters = (array)$parameters;
		
		return $parameters;
	}
}