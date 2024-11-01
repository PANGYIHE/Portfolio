using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class InfoActive : MonoBehaviour
{
    public GameObject InfoPanel;

    // Start is called before the first frame update
    void Start()
    {
        
    }

    // Update is called once per frame
    void Update()
    {
        
    }

    public void ToggleInfoPanel()
    {
        // Check if InfoPanel is active
        if (InfoPanel.activeSelf)
        {
            // If active, deactivate it
            InfoPanel.SetActive(false);
        }
        else
        {
            // If inactive, activate it
            InfoPanel.SetActive(true);
        }
    }
}
