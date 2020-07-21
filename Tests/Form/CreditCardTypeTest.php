<?php
/**
 * This file is part of Stripe4
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 * https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Stripe4\Tests\Form;


use Eccube\Entity\Customer;
use Eccube\Tests\Form\Type\AbstractTypeTestCase;
use Plugin\Stripe4\Entity\CreditCard;
use Plugin\Stripe4\Form\Type\CreditCardType;
use Plugin\Stripe4\Repository\CreditCardRepository;
use Symfony\Component\Form\FormInterface;

class CreditCardTypeTest extends AbstractTypeTestCase
{
    /** @var FormInterface */
    protected $form;

    /** @var CreditCardRepository */
    protected $creditCardRepository;

    /** @var CreditCard  */
    protected $creditCard;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $container = self::$kernel->getContainer();

        $this->creditCardRepository = $container->get(CreditCardRepository::class);

        $Customer = $this->createCustomer();
        $this->creditCard = $this->createCreditCard($Customer);

        $this->form = $this->formFactory
            ->createBuilder(CreditCardType::class, null, [
                'csrf_protection' => false
            ])
            ->getForm();
    }

    public function tearDown()
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    public function test正常テスト()
    {
        $this->form->submit($this->creditCard->getStripePaymentMethodId());
        self::assertTrue($this->form->isValid());
        self::assertEquals($this->form->getData(), $this->creditCardRepository->find($this->creditCard->getId()));
    }

    protected function createCreditCard(Customer $customer)
    {
        $faker = $this->getFaker();

        $creditCard = new CreditCard();
        $creditCard
            ->setCustomer($customer)
            ->setStripeCustomerId($faker->word)
            ->setStripePaymentMethodId($faker->word)
            ->setFingerprint($faker->word)
            ->setBrand($faker->word)
            ->setLast4($faker->word);
        $this->entityManager->persist($creditCard);
        $this->entityManager->flush();

        return $creditCard;
    }

}
