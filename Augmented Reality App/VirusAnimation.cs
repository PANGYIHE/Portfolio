using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class VirusAnimation : MonoBehaviour
{
    private Animator Anim;

    // Start is called before the first frame update
    void Start()
    {
        Anim = GetComponent<Animator>();
        Anim.speed = 0f;
    }

    // Update is called once per frame
    void Update()
    {
        
    }
    public void AniRotate()
    {
        Anim.Play("VirusRotate", -1, 0f);
        Anim.speed = 1f;
    }

    public void AniScale()
    {
        Anim.Play("VirusZoomIn", -1, 0f);
        Anim.speed = 1f;
    }
}
