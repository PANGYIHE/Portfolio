using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class EnemyFollowPlayer : MonoBehaviour
{
    public float speed;
    public float lineOfSite;
    private float originalLineOfSight;
    private Transform player;
    private bool isLineOfSightDisabled = false;

    // Start is called before the first frame update
    void Start()
    {
        player = GameObject.FindGameObjectWithTag("Player").transform;
        originalLineOfSight = lineOfSite;
    }

    // Update is called once per frame
    void Update()
    {
        if (player != false)
        {
            float distanceFromPlayer = Vector2.Distance(player.position, transform.position);
            if (distanceFromPlayer < lineOfSite)
            {
                transform.position = Vector2.MoveTowards(this.transform.position, player.position, speed);

                if (transform.position.x > player.position.x)
                {
                    gameObject.transform.rotation = Quaternion.Euler(0, 0, 0);
                }
                if (transform.position.x < player.position.x)
                {
                    gameObject.transform.rotation = Quaternion.Euler(0, 180, 0);
                }
            }
        }
    }

    private void OnDrawGizmosSelected()
    {
        Gizmos.color = Color.green;
        Gizmos.DrawWireSphere(transform.position, lineOfSite);
    }

    public void DisableLineOfSightForDuration(float duration)
    {
        StartCoroutine(DisableLineOfSightCoroutine(duration));
    }

    private IEnumerator DisableLineOfSightCoroutine(float duration)
    {
        isLineOfSightDisabled = true;
        lineOfSite = 0f;

        yield return new WaitForSeconds(duration);

        isLineOfSightDisabled = false;
        lineOfSite = originalLineOfSight; // Reset to the original line of sight value
    }
}