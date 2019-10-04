<?php

namespace HgaCreative\MailgunWebhook\Traits;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

/**
 * Automate the assignment of a uuid as the primary key
 * for a given record persisted to the database
 *
 * @since 1.0.0
 */
trait AutomateUuid {

    /**
	 * Hook onto the boot method
	 *
	 * @return void
	 */
	protected static function bootAutomateUuid()
	{
		/**
		 * When creating a new entry for a model automatically
		 * generate the UUID far the order.
		 */
		static::creating(function($model) {

			try {

				// if no UUID is provided, then we should create one
				// this is only really used when we're seeding
                if(!$model->id) $model->{$model->primaryKey} = Uuid::uuid4()->toString();

            } catch(UnsatisfiedDependencyException $e) {

                die($e->getMessage());

            }

		});

		/**
		 * When saving an existing entry for a model,
		 * prevent the model id being overwritten
		 */
		static::saving(function($model) {

            $original_uuid = $model->getOriginal($model->{$model->primaryKey});

            if($original_uuid && $original_uuid !== $model->{$model->primaryKey}) $model->{$model->primaryKey} = $original_uuid;

        });
	}

}
