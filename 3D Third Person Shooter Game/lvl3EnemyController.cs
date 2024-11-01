using TMPro;
using UnityEngine;

public class EnemyController : MonoBehaviour
{
    public Transform target1, target2, target3, target4, player;
    UnityEngine.AI.NavMeshAgent agent;
    bool escape = true;
    public string playerTag = "Player"; // Tag of the player GameObject
    private Animator animator; // Reference to the animator component

    public TextMeshProUGUI killCountText;
    public int Nhealth = 2, Ghealth = 5, Bhealth = 8;
    public static int killCount = 0;

    public GameObject silvercoinPrefab;
    public GameObject goldcoinPrefab;
    [SerializeField] private AudioSource zombieSound;
    [SerializeField] private AudioSource zombieDieSound;

    // Start is called before the first frame update
    void Start()
    {
        agent = GetComponent<UnityEngine.AI.NavMeshAgent>();
        animator = GetComponent<Animator>();
        zombieSound.Play();
    }

    // Update is called once per frame
    void Update()
    {
        killCountText.text = "Kill: " + killCount + "/40";

        if (Vector3.Distance(player.position, transform.position) < 15)
        {
            agent.SetDestination(player.position);
            escape = true;

            // Check if distance is less than 3 units and set isAttacking to true
            if (Vector3.Distance(player.position, transform.position) <= 2)
            {
                animator.SetBool("isAttacking", true);
            }
            else
            {
                // Set isAttacking to false when the distance is greater than 3 units
                animator.SetBool("isAttacking", false);
            }
        }
        else
        {
            if ((Vector3.Distance(target3.position, transform.position)) < 0.5f)
            {
                agent.SetDestination(target1.position);
            }
            else if ((Vector3.Distance(target1.position, transform.position)) < 0.5f)
            {
                agent.SetDestination(target2.position);
            }
            else if ((Vector3.Distance(target2.position, transform.position)) < 0.5f)
            {
                agent.SetDestination(target3.position);
            }
            else if ((Vector3.Distance(target3.position, transform.position)) < 0.5f)
            {
                agent.SetDestination(target4.position);
            }

            if (escape)
            {
                escape = false;
                agent.SetDestination(target1.position);
            }
        }
    }

    void OnCollisionEnter(Collision other)
    {
        if (gameObject.CompareTag("normalZombie"))
        {
            // Check if the collided object is tagged as a bullet
            if (other.gameObject.CompareTag("bullet"))
            {
                // Reduce enemy health
                Nhealth--;
                HandleDeath();
            }
        }


        if (gameObject.CompareTag("ghoulZombie"))
        {
            // Check if the collided object is tagged as a bullet
            if (other.gameObject.CompareTag("bullet"))
            {
                // Reduce enemy health
                Ghealth--;
                HandleDeath();
            }
        }

        if (gameObject.CompareTag("bossZombie"))
        {
            // Check if the collided object is tagged as a bullet
            if (other.gameObject.CompareTag("bullet"))
            {
                // Reduce enemy health
                Bhealth--;
                HandleDeath();
            }
        }
    }

    void OnTriggerEnter(Collider col)
    {
        if (gameObject.CompareTag("normalZombie"))
        {
            // Check if the collided object is tagged as a grenade
            if (col.CompareTag("explosion"))
            {
                // Reduce enemy health by 3
                Nhealth -= 3;
                HandleDeath();
            }
        }

        if (gameObject.CompareTag("ghoulZombie"))
        {
            // Check if the collided object is tagged as a bullet
            if (col.CompareTag("explosion"))
            {
                // Reduce enemy health
                Ghealth -= 3;
                HandleDeath();
            }
        }

        if (gameObject.CompareTag("bossZombie"))
        {
            // Check if the collided object is tagged as a bullet
            if (col.CompareTag("explosion"))
            {
                // Reduce enemy health
                Bhealth -= 3;
                HandleDeath();
            }
        }
    }

    private void HandleDeath()
    {
        if (Nhealth <= 0)
        {
            // Change the tag of the zombie to "deadZombie"
            gameObject.tag = "deadZombie";

            animator.SetBool("isDead", true);// Destroy the enemy GameObject
            agent.speed = 0f;
            killCount++;

            zombieSound.Stop();
            zombieDieSound.Play();

            // Spawn two coin GameObjects
            Instantiate(silvercoinPrefab, transform.position + new Vector3(0, 0.5f, 0), Quaternion.identity);
            Instantiate(silvercoinPrefab, transform.position + new Vector3(1, 0.5f, 0), Quaternion.identity);

            Destroy(gameObject, 3f);
        }

        if (Ghealth <= 0)
        {
            // Change the tag of the zombie to "deadZombie"
            gameObject.tag = "deadZombie";

            animator.SetBool("isDead", true);// Destroy the enemy GameObject
            agent.speed = 0f;
            killCount++;

            zombieSound.Stop();
            zombieDieSound.Play();

            // Spawn two coin GameObjects
            Instantiate(goldcoinPrefab, transform.position + new Vector3(0, 0.5f, 0), Quaternion.identity);
            Instantiate(goldcoinPrefab, transform.position + new Vector3(1, 0.5f, 0), Quaternion.identity);

            Destroy(gameObject, 3f);
        }

        if (Bhealth <= 0)
        {
            // Change the tag of the zombie to "deadZombie"
            gameObject.tag = "deadZombie";

            animator.SetBool("isDead", true);// Destroy the enemy GameObject
            agent.speed = 0f;
            killCount++;

            zombieSound.Stop();
            zombieDieSound.Play();

            // Spawn two coin GameObjects
            Instantiate(goldcoinPrefab, transform.position + new Vector3(0, 0.5f, 0), Quaternion.identity);
            Instantiate(goldcoinPrefab, transform.position + new Vector3(1, 0.5f, 0), Quaternion.identity);
            Instantiate(goldcoinPrefab, transform.position + new Vector3(0, 0.5f, 1), Quaternion.identity);

            Destroy(gameObject, 3f);
        }
    }
}