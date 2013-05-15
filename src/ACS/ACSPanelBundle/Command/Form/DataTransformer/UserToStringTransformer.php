<?php
namespace ACS\ACSPanelBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use ACS\ACSPanelBundle\Entity\FosUser;

class UserToStringTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (user) to a string (number).
     *
     * @param  user|null $user
     * @return string
     */
    public function transform($user)
    {
      return $user;
    }

    /**
     * Transforms a string (number) to an object (user).
     *
     * @param  string $number
     * @return user|null
     * @throws TransformationFailedException if object (user) is not found.
     */
    public function reverseTransform($number)
    {
        if (!$number) {
            return null;
        }

        $user = $this->om
            ->getRepository('ACSACSPanelBundle:FosUser')
            ->findOneBy(array('id' => $number))
        ;

        if (null === $user) {
            throw new TransformationFailedException(sprintf(
                'An user with id "%s" does not exist!',
                $number
            ));
        }

        return $user;
    }
}
