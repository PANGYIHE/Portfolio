using System.Collections;
using UnityEngine;

public class PowerUp : MonoBehaviour
{
    public GameObject particleHealth, particleAmmo, particleShield;
    GameObject player;
    PlayerController playerController;
    [SerializeField] private AudioSource powerUpSound;

    // Start is called before the first frame update
    void Start()
    {
        player = GameObject.Find("Player");
        playerController = player.GetComponent<PlayerController>();
    }

    // Update is called once per frame
    void Update()
    {
        transform.Rotate(Vector3.up * 100 * Time.deltaTime);
    }

    void OnCollisionEnter(Collision col)
    {
        if (col.gameObject.name == "Player")
        {
            if (gameObject.tag == "potion")
            {
                playerController.healthBar.value += 10;
                powerUpSound.Play();
                Instantiate(particleHealth, transform.position, transform.rotation);
                Destroy(gameObject);
            }
            else if (gameObject.tag == "ammo")
            {
                playerController.ammoBuff = 1;
                powerUpSound.Play();
                Instantiate(particleAmmo, transform.position, transform.rotation);
                Destroy(gameObject);
            }
            else if (gameObject.tag == "shield")
            {
                playerController.shieldBar.value += 20;
                powerUpSound.Play();
                Instantiate(particleShield, transform.position, transform.rotation);
                Destroy(gameObject);
            }
        }
    }
}
