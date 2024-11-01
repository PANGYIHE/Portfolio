using UnityEngine;
using System.Collections;
using UnityEngine.UI;
using Platformer;
using System.Collections.Generic;

public class TimeChallenge : MonoBehaviour
{
    public GameObject timeChallengeStart;
    public GameObject timeChallengeStop;
    [SerializeField] private GameObject timeChallengeTopUI;
    [SerializeField] private Text timerS;
    [SerializeField] private GameObject timerChallengeCanvas;
    [SerializeField] private Button resumeButton;
    [SerializeField] private GameObject timerPassCanvas;
    [SerializeField] private GameObject timerFailCanvas;
    [SerializeField] private AudioSource completeEffect;
    [SerializeField] private AudioSource failEffect;
    [SerializeField] private AudioSource timerEffect;

    private bool isChallengeActive = false;
    private float challengeDuration = 10f;
    private int applesReward = 10;
    private int orangesReward = 5;

    private void Start()
    {
        resumeButton.onClick.AddListener(ResumeGame);
    }
    private void OnTriggerEnter2D(Collider2D other)
    {
        if (other.gameObject == timeChallengeStart && !isChallengeActive)
        {
            // Start the time challenge
            timerEffect.Play();
            StartCoroutine(StartChallenge());
        }
        else if (other.gameObject == timeChallengeStop && isChallengeActive)
        {
            // Complete the challenge
            CompleteChallenge();
        }
    }

    private IEnumerator StartChallenge()
    {
        isChallengeActive = true;

        // Display any UI or effects indicating the start of the challenge
        if (timeChallengeTopUI != null)
        {
            timeChallengeTopUI.gameObject.SetActive(true);
        }

        if (timerChallengeCanvas != null)
        {
            timerChallengeCanvas.gameObject.SetActive(true);
            Time.timeScale = 0f;
        }

        StartCoroutine(StartCountdown());

        // Wait for the challenge duration
        yield return new WaitForSeconds(challengeDuration);

        // If the player hasn't completed the challenge in time, fail the challenge
        FailChallenge();
        timerEffect.Stop();

    }

    private void CompleteChallenge()
    {
        // Stop the countdown
        StopAllCoroutines();
        timerEffect.Stop();

        if (timeChallengeTopUI != null)
        {
            timeChallengeTopUI.gameObject.SetActive(false);
        }

        if (timerPassCanvas != null)
        {
           timerPassCanvas.gameObject.SetActive(true);
        }

        StartCoroutine(DeactivateTimePassCanvas(5f));

        // Reset the challenge state
        isChallengeActive = false;

        // Reward the player
        RewardPlayer();
    }

    private void FailChallenge()
    {
        
        if (timeChallengeTopUI != null)
        {
            timeChallengeTopUI.gameObject.SetActive(false);
        }

        if (timerFailCanvas != null)
        {
            timerFailCanvas.gameObject.SetActive(true);
        }

        StartCoroutine(DeactivateTimeFailCanvas(5f));

        // Reset the challenge state
        isChallengeActive = false;

    }

    private IEnumerator StartCountdown()
    {
        while (challengeDuration > 0)
        {
            // Update the timerS Text with the countdown value
            timerS.text = challengeDuration.ToString("F1") + "s";

            // Wait for a short duration before decrementing the countdown
            yield return new WaitForSeconds(0.1f);

            // Decrement the countdown
            challengeDuration -= 0.1f;
        }

        // When the countdown reaches 0, hide the UI and resume the game
        FailChallenge(); // Call FailChallenge when the countdown is over
    }
    private IEnumerator DeactivateTimePassCanvas(float delay)
    {
        completeEffect.Play();
        yield return new WaitForSeconds(delay);

        if (timerPassCanvas != null)
        {
            timerPassCanvas.gameObject.SetActive(false);
        }
    }
    private IEnumerator DeactivateTimeFailCanvas(float delay)
    {
        failEffect.Play();
        yield return new WaitForSeconds(delay);

        if (timerFailCanvas != null)
        {
            timerFailCanvas.gameObject.SetActive(false);
        }
    }

    private void RewardPlayer()
    {

        ItemCollect itemCollect = GameObject.FindObjectOfType<ItemCollect>();

        if (itemCollect != null)
        {
            // Update the apple and orange counts in ItemCollect
            itemCollect.apple += applesReward;
            itemCollect.orange += orangesReward;

            // Update the UI in ItemCollect (if needed)
            if (itemCollect.pointText != null)
            {
                itemCollect.pointText.text = itemCollect.apple + "/80";
            }
            if (itemCollect.pointText1 != null)
            {
                itemCollect.pointText1.text = itemCollect.orange + "/40";
            }
        }
    }
    private void ResumeGame()
    {
        // Set Time.timeScale to 1 to resume the game
        Time.timeScale = 1f;
    }
}