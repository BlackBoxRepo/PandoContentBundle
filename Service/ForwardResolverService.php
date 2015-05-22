<?php
namespace BlackBoxCode\Pando\ContentBundle\Service;

use BlackBoxCode\Pando\ContentBundle\Document\FormDocument;
use BlackBoxCode\Pando\ContentBundle\Document\FormPageDocument;
use BlackBoxCode\Pando\ContentBundle\Document\PageDocument;
use BlackBoxCode\Pando\ContentBundle\Exception\Service\NoFormPageException;
use Symfony\Component\Form\FormInterface;

class ForwardResolverService
{
    /**
     * Finds the success or failure page from the given Page/Form
     *
     * @param PageDocument $page
     * @param FormInterface $form
     * @param bool $isValid
     *
     * @throws NoFormPageException if no FormPage is found
     * @return PageDocument
     */
    public function resolve(PageDocument $page, FormInterface $form, $isValid)
    {
        $formName = $form->getName();

        /** @var FormPageDocument $formPage */
        if ($formPage = $page->getFormPageByFormName($formName)) {
            return $isValid ? $formPage->getSuccessPage() : $formPage->getFailurePage();
        }

        throw new NoFormPageException(sprintf('The page "%s" does not have any FormPageDocuments that contain the "%s" form', $page->getName(), $formName));
    }
}
