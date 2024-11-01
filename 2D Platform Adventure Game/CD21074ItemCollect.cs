using Platformer;
using System.Collections;
using System.Collections.Generic;
using TMPro;
using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;

public class ItemCollect : MonoBehaviour
{
    public int apple = 0;

    [SerializeField] public Text pointText;
    [SerializeField] private AudioSource collectSoundEffect;

    public int orange = 0;

    [SerializeField] public Text pointText1;
    [SerializeField] private AudioSource collectSoundEffect2;

    private bool isMagneticActive = false;
    private float magneticDuration = 12f;
    private float magneticRadius = 8f;
    [SerializeField] private AudioSource collectSoundEffect3;
    [SerializeField] private AudioSource healSoundEffect;
    [SerializeField] private AudioSource finishEffect;
    [SerializeField] private AudioSource failEffect;

    public GameObject Attractor;
    public GameObject MagicalRing;
    public GameObject MagicalRing2;
    public GameObject MagicalRing3;
    public GameObject MagicalRing4;

    private EnemyFollowPlayer enemyFollowPlayer; // Reference to the EnemyFollowPlayer script

    [SerializeField] private GameObject finishCanvas;
    [SerializeField] private GameObject failCanvas;
    [SerializeField] private List<Button> resumeButtons = new List<Button>();
    [SerializeField] private GameObject magicTreeCanvas;
    [SerializeField] private GameObject magicLeafCanvas;
    [SerializeField] private GameObject magicLeafTopUI;
    [SerializeField] private Text leafS;
    [SerializeField] private GameObject magicShoeCanvas;
    [SerializeField] private GameObject magicShoeTopUI;
    [SerializeField] private Text shoeS;
    [SerializeField] private GameObject magicMagnetCanvas;
    [SerializeField] private GameObject magicMagnetTopUI;
    [SerializeField] private Text magnetS;
    [SerializeField] private GameObject magicPotionCanvas;
    [SerializeField] private GameObject magicPotionTopUI;
    [SerializeField] private Text potionS;
    [SerializeField] private ParticleSystem congrateParticleEffect;
    [SerializeField] private ParticleSystem congrateParticleEffect2;
    [SerializeField] private ParticleSystem congrateParticleEffect3;

    private float leafCountdown = 10.0f;
    private float shoeCountdown = 15.0f;
    private float magnetCountdown = 10.0f;
    private float potionCountdown = 15.0f;

    private void Start()
    {
        // Add an onClick event handler for the resume button
        foreach (Button button in resumeButtons)
        {
            button.onClick.AddListener(ResumeGame);
        }
    }
    private void OnTriggerEnter2D(Collider2D collision)
    {
        if (collision.gameObject.CompareTag("Apple"))
        {
            collectSoundEffect.Play();
            Destroy(collision.gameObject);
            apple++;
            pointText.text = apple + "/80";
        }
        else if (collision.gameObject.CompareTag("Orange"))
        {
            collectSoundEffect2.Play();
            Destroy(collision.gameObject);
            orange++;
            pointText1.text = orange + "/40";
        }
        else if (collision.gameObject.CompareTag("MagicLeaf"))
        {
            collectSoundEffect3.Play();
            Destroy(collision.gameObject);

            // Assuming your player has a PlayerController script attached
            PlayerController playerController = FindObjectOfType<PlayerController>();

            if (playerController != null)
            {
                // Apply speed boost to the player
                playerController.ApplySpeedBoost(5.5f, 10f);
            }

            if (MagicalRing != null)
            {
                MagicalRing.SetActive(true);
                magicLeafTopUI.SetActive(true);
                StartCoroutine(DeactivateMagicalRingAfterDelay(10f));
            }

            if (magicLeafCanvas != null)
            {
                magicLeafCanvas.gameObject.SetActive(true);
                Time.timeScale = 0f;
            }

            StartCoroutine(StartLeafCountdown());
        }
        else if (collision.gameObject.CompareTag("MagicShoe"))
        {
            collectSoundEffect3.Play();
            Destroy(collision.gameObject);

            // Assuming your player has a PlayerController script attached
            PlayerController playerController = FindObjectOfType<PlayerController>();

            if (playerController != null)
            {
                // Apply speed boost to the player
                playerController.ApplyJumpBoost(5f, 15f);
            }

            if (MagicalRing2 != null)
            {
                MagicalRing2.SetActive(true);
                magicShoeTopUI.SetActive(true);
                StartCoroutine(DeactivateMagicalRing2AfterDelay(15f));
            }

            if (magicShoeCanvas != null)
            {
                magicShoeCanvas.gameObject.SetActive(true);
                Time.timeScale = 0f;
            }

            StartCoroutine(StartShoeCountdown());
        }
        else if (collision.gameObject.CompareTag("MagicMagnet"))
        {
            collectSoundEffect3.Play();
            Destroy(collision.gameObject);

            // Activate the Attractor game object
            if (Attractor != null)
            {
                Attractor.SetActive(true);

                // Deactivate the Attractor after 12 seconds
                StartCoroutine(DeactivateAttractorAfterDelay(10f));
            }

            if (MagicalRing3 != null)
            {
                MagicalRing3.SetActive(true);
                magicMagnetTopUI.SetActive(true);
                StartCoroutine(DeactivateMagicalRing3AfterDelay(10f));
            }

            if (magicMagnetCanvas != null)
            {
                magicMagnetCanvas.gameObject.SetActive(true);
                Time.timeScale = 0f;
            }

            StartCoroutine(StartMagnetCountdown());
        }
        else if (collision.gameObject.CompareTag("MagicPotion"))
        {
            collectSoundEffect3.Play();
            Destroy(collision.gameObject);

            // Disable the enemy's line of sight for 12 seconds
            DisableLineOfSightForDuration(15f);

            if (MagicalRing4 != null)
            {
                MagicalRing4.SetActive(true);
                magicPotionTopUI.SetActive(true);
                StartCoroutine(DeactivateMagicalRing4AfterDelay(15f));
            }

            if (magicPotionCanvas != null)
            {
                magicPotionCanvas.gameObject.SetActive(true);
                Time.timeScale = 0f;
            }

            StartCoroutine(StartPotionCountdown());
        }

        if (collision.gameObject.CompareTag("Finish"))
        {
            if (apple >= 80 && orange >= 40)
            {
                // Activate the Finish Canvas
                if (finishCanvas != null)
                {
                    finishEffect.Play();
                    finishCanvas.gameObject.SetActive(true);
                    congrateParticleEffect.Play();
                    congrateParticleEffect2.Play();
                    congrateParticleEffect3.Play();
                }
            }
            else
            {
                // Activate the Fail Canvas
                if (failCanvas != null)
                {
                    failEffect.Play();
                    failCanvas.gameObject.SetActive(true);
                    Time.timeScale = 0f;
                }
            }
        }

        if (collision.gameObject.CompareTag("MagicTree"))
        {
            collectSoundEffect3.Play();

            if (magicTreeCanvas != null)
            {
                magicTreeCanvas.gameObject.SetActive(true);
                Time.timeScale = 0f;
            }
        }

        if (collision.gameObject.CompareTag("Heart"))
        {
            PlayerController playerController = FindObjectOfType<PlayerController>();

            if (playerController.heartsCount != 3)
            {
                healSoundEffect.Play();

                Destroy(collision.gameObject);

                // Increase hearts count
                playerController.heartsCount++;

                // Activate the next heart
                if (playerController.heartsCount <= playerController.hearts.Length)
                {
                    playerController.hearts[playerController.heartsCount - 1].enabled = true;
                }
            }
        }
    }

    private void ResumeGame()
    {
        // Set Time.timeScale to 1 to resume the game
        Time.timeScale = 1f;
    }
    private void DisableLineOfSightForDuration(float duration)
    {
        // Find all GameObjects with the "Enemy" tag
        GameObject[] enemies = GameObject.FindGameObjectsWithTag("Enemy");

        foreach (GameObject enemy in enemies)
        {
            // Get the EnemyFollowPlayer script from each enemy and disable the line of sight
            EnemyFollowPlayer enemyFollowPlayer = enemy.GetComponent<EnemyFollowPlayer>();

            if (enemyFollowPlayer != null)
            {
                enemyFollowPlayer.DisableLineOfSightForDuration(duration);
            }
        }
    }
    private IEnumerator DeactivateAttractorAfterDelay(float delay)
    {
        yield return new WaitForSeconds(delay);

        // Deactivate the Attractor game object
        if (Attractor != null)
        {
            Attractor.SetActive(false);
        }
    }
    private IEnumerator DeactivateMagicalRingAfterDelay(float delay)
    {
        yield return new WaitForSeconds(delay);

        // Deactivate the Attractor game object
        if (MagicalRing != null)
        {
            MagicalRing.SetActive(false);
            magicLeafTopUI.SetActive(false);
        }
    }
    private IEnumerator DeactivateMagicalRing2AfterDelay(float delay)
    {
        yield return new WaitForSeconds(delay);

        // Deactivate the Attractor game object
        if (MagicalRing2 != null)
        {
            MagicalRing2.SetActive(false);
            magicShoeTopUI.SetActive(false);
        }
    }
    private IEnumerator DeactivateMagicalRing3AfterDelay(float delay)
    {
        yield return new WaitForSeconds(delay);

        // Deactivate the Attractor game object
        if (MagicalRing3 != null)
        {
            MagicalRing3.SetActive(false);
            magicMagnetTopUI.SetActive(false);
        }
    }
    private IEnumerator DeactivateMagicalRing4AfterDelay(float delay)
    {
        yield return new WaitForSeconds(delay);

        // Deactivate the Attractor game object
        if (MagicalRing4 != null)
        {
            MagicalRing4.SetActive(false);
            magicPotionTopUI.SetActive(false);
        }
    }

    private IEnumerator StartLeafCountdown()
    {
        while (leafCountdown > 0)
        {
            // Update the leafS Text with the countdown value
            leafS.text = leafCountdown.ToString("F1") + " s";

            // Wait for a short duration before decrementing the countdown
            yield return new WaitForSeconds(0.1f);

            // Decrement the countdown
            leafCountdown -= 0.1f;
        }

        // When the countdown reaches 0, hide the UI and resume the game
        Time.timeScale = 1f;
    }

    private IEnumerator StartShoeCountdown()
    {
        while (shoeCountdown > 0)
        {
            // Update the leafS Text with the countdown value
            shoeS.text = shoeCountdown.ToString("F1") + " s";

            // Wait for a short duration before decrementing the countdown
            yield return new WaitForSeconds(0.1f);

            // Decrement the countdown
            shoeCountdown -= 0.1f;
        }

        // When the countdown reaches 0, hide the UI and resume the game
        Time.timeScale = 1f;
    }

    private IEnumerator StartMagnetCountdown()
    {
        while (magnetCountdown > 0)
        {
            // Update the leafS Text with the countdown value
            magnetS.text = magnetCountdown.ToString("F1") + " s";

            // Wait for a short duration before decrementing the countdown
            yield return new WaitForSeconds(0.1f);

            // Decrement the countdown
            magnetCountdown -= 0.1f;
        }

        // When the countdown reaches 0, hide the UI and resume the game
        Time.timeScale = 1f;
    }

    private IEnumerator StartPotionCountdown()
    {
        while (potionCountdown > 0)
        {
            // Update the leafS Text with the countdown value
            potionS.text = potionCountdown.ToString("F1") + " s";

            // Wait for a short duration before decrementing the countdown
            yield return new WaitForSeconds(0.1f);

            // Decrement the countdown
            potionCountdown -= 0.1f;
        }

        // When the countdown reaches 0, hide the UI and resume the game
        Time.timeScale = 1f;
    }
}   