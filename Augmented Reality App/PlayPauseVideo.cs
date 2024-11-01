using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.Video;
using UnityEngine.Sprites;
using UnityEngine.UI;

public class PlayPauseVideo : MonoBehaviour
{

    private VideoPlayer player;
    public Button button;
    public GameObject notifyUI;

    // Start is called before the first frame update
    void Start()
    {
        player = GetComponent<VideoPlayer>();
        player.SetDirectAudioMute(0, false);

    }

    // Update is called once per frame
    void Update()
    {

    }

    public void ChangeStartStop()
    {
        bool isMuted = player.GetDirectAudioMute(0);

        if (player.isPlaying == false)
        {
            player.Play();
            ActivateNotifyUI(isMuted);
        }
        else
        {
            player.Pause();
            DeactivateNotifyUI();
        }
    }

    public void ToggleMute()
    {
        bool isMuted = player.GetDirectAudioMute(0);
        player.SetDirectAudioMute(0, !isMuted);

        if (player.isPlaying)
        {
            ActivateNotifyUI(!isMuted);
        }
    }

    private void ActivateNotifyUI(bool activate)
    {
        if (notifyUI != null)
        {
            notifyUI.SetActive(activate);
        }
    }

    private void DeactivateNotifyUI()
    {
        ActivateNotifyUI(false);
    }
}