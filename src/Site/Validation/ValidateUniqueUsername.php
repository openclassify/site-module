<?php namespace Visiosoft\SiteModule\Site\Validation;

use Anomaly\Streams\Platform\Ui\Form\FormBuilder;
use Anomaly\UsersModule\User\UserPassword;
use Visiosoft\SiteModule\Helpers\Formatters;
use Visiosoft\SiteModule\Helpers\Validation;

/**
 * Class ValidatePassword
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class ValidateUniqueUsername
{

    /**
     * Handle the validation.
     *
     * @param FormBuilder $builder
     * @param UserPassword $password
     * @param              $attribute
     * @param              $value
     * @return true
     */
    public function handle(FormBuilder $builder): bool
    {
        $username = (new Formatters())->cleanUsername($builder->getPostData()['username']);
        $entryId = $builder->getFormEntryId();

        $isSiteExist = (new Validation())->isUsernameUnique($username, $entryId);

        if ($isSiteExist) {
            $builder->addFormError('email', trans('visiosoft.module.site::message.username_already_taken'));
            return false;
        } else {
            return true;
        }
    }
}
