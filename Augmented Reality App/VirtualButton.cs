using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using Vuforia;

public class VirtualButton : MonoBehaviour, IVirtualButtonEventHandler
{
    public GameObject vButton;
    public Animator quickAni;
    public AudioSource virtualAudio;

    // Start is called before the first frame update
    void Start()
    {
        vButton = GameObject.Find("VirtualButton");
        vButton.GetComponent<VirtualButtonBehaviour>().RegisterEventHandler(this);
        quickAni.GetComponent<Animator>();
    }

    public void OnButtonPressed(VirtualButtonBehaviour vb)
    {
        virtualAudio.Play();
        quickAni.Play("quickAnimation", -1, 0f);
        quickAni.speed = 1f;
        Debug.Log("BTN Pressed");
    }

    public void OnButtonReleased(VirtualButtonBehaviour vb)
    {
        virtualAudio.Stop();
        quickAni.Play("none");
        Debug.Log("BTN Released");
    }

    // Update is called once per frame
    void Update()
    {
        
    }
}
