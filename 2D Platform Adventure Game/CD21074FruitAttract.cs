using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class FruitAttract : MonoBehaviour
{
    public float attractorStrength = 5f; // Strength of the attraction force
    public float attractorRange = 5f; // Range of the attraction force

    void FixedUpdate()
    {
        Collider2D[] hitColliders = Physics2D.OverlapCircleAll(transform.position, attractorRange);
        foreach (Collider2D hitCollider in hitColliders)
        {
            if (hitCollider.CompareTag("Apple") || hitCollider.CompareTag("Orange"))
            {
                Vector3 forceDirection = transform.position - hitCollider.transform.position;
                hitCollider.GetComponent<Rigidbody2D>().AddForce(forceDirection.normalized * attractorStrength);
            }
        }
    }

    private void OnDrawGizmos()
    {
        Gizmos.color = Color.red;
        Gizmos.DrawWireSphere(transform.position, attractorRange);
    }
}
