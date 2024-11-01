using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Bullet : MonoBehaviour
{
    public GameObject explosion;
    public GameObject explosionRange;
    [SerializeField] private AudioSource explodeSound;

    void Start()
    {
        // The projectile is deleted after 10 seconds, whether
        // or not it collided with anything (to prevent lost
        // instances sticking around in the scene forever)
        Destroy(gameObject, 10);
    }
    void OnCollisionEnter()
    {
        // it hit something: create an explosion, and remove the projectile
        if (gameObject.tag == "collectedGrenade")
        {
            explodeSound.Play();
            explosionRange.SetActive(true);
            Instantiate(explosion, transform.position, transform.rotation);
            Destroy(gameObject, 1f);
        }
        else
        {

            Destroy(gameObject, 0.25f);
        }
    }
}
